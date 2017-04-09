/**
 *  Gulp Config
 */
var gulp = require('gulp'),
    sass = require('gulp-sass'),
    rename = require('gulp-rename'),
    uglify = require('gulp-uglify'),
    sourcemaps = require('gulp-sourcemaps'),
    postcss = require('gulp-postcss'),
    autoprefixer = require('autoprefixer'),
    gutil = require('gulp-util'),
    path = require('path'),
    process = require('process'),
    scsslint = require('gulp-scss-lint'),
    eslint = require('gulp-eslint'),
    phpcs = require('gulp-phpcs'),
    runSequence = require('run-sequence'),
    compiler = require('google-closure-compiler-js').compile,
    each = require('gulp-each'),
    SSHClient = require('ssh2').Client,
    pluginFolder = path.join( '/tmp/wordpress/wp-content/themes/', path.basename( __dirname ) );
    // currentFolder = path.basename(__dirname),
    // projectFolderServerPath = path.join(basePath, currentFolder);


// Folders
const ROOT = __dirname;
const ASSETS = path.join(ROOT, 'assets');
const JS = path.join(ASSETS, 'js');
const CSS = path.join(ASSETS, 'css');
const SCSS = path.join(ASSETS, 'sass');

// Globs
const ALL_SCSS_FILES = [ path.join(SCSS, '**/*.scss') ];
const MAIN_SCSS_FILES = [ path.join(SCSS, '**/*.scss'), '!' + path.join(SCSS, '**/_*.scss') ];
const JS_FILES = [ path.join(JS, '*.js'), '!' + path.join(JS, '*.min.js') ];
const PHP_FILES = [ path.join(ROOT, '**/*.php') ];


/**
 *  Production-ready JavaScript
 */
gulp.task('js:build:production', function() {
    return gulp.src(JS_FILES)
        .pipe(uglify().on('error', gutil.log))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(JS));
});


/**
 *  JavaScript Linting
 */
gulp.task('js:lint', function() {
    return gulp.src(JS_FILES)
        .pipe(eslint())
        .pipe(eslint.format());
});


/**
 *  Development Version CSS
 */
gulp.task('scss:build:development', function() {
    return gulp.src(MAIN_SCSS_FILES)
        .pipe(sourcemaps.init())
        .pipe(sass().on('error', sass.logError))
        .pipe(postcss([ autoprefixer({ browsers: ['> 5%', 'IE 9'] }) ]))
        .pipe(sourcemaps.write())
        .pipe(gulp.dest(CSS));
});


/**
 *  Watch SCSS
 */
gulp.task('scss:watch', ['scss:build:development'], function() {
    return gulp.watch(ALL_SCSS_FILES, ['scss:build:development']);
});


/**
 *  Production ready CSS
 */
gulp.task('scss:build:production', function() {
    return gulp.src(MAIN_SCSS_FILES)
        .pipe(sass({outputStyle: 'compressed'}).on('error', sass.logError))
        .pipe(postcss([ autoprefixer({ browsers: ['> 5%', 'IE 9'] }) ]))
        .pipe(rename({ suffix: '.min' }))
        .pipe(gulp.dest(CSS));
});


/**
 *  Scss Linting
 */
gulp.task('scss:lint', function() {
    return gulp.src(ALL_SCSS_FILES)
        .pipe(scsslint({
            config: '.scss-lint.yml'
        }));
});


/**
 *  PHP Linting
 */
gulp.task('php:lint', function() {
    return gulp.src(PHP_FILES)
        .pipe(phpcs({
            standard: path.join(__dirname, 'phpcs.ruleset.xml')
        }))
        .pipe(phpcs.reporter('log'));
});


function runCommandOnVM(command, cb) {
    var conn = new SSHClient();
    gutil.log('Connecting to VM...');
    conn.on('ready', function() {
        gutil.log('Connected to VM');
        gutil.log('Run:', gutil.colors.cyan(command));
        conn.exec(command, {
            'pty': {
                'term': 'xterm-color'
            }
        }, function(err, stream) {
            if (err) throw err;

            stream.on('close', function() {
                // Close SSH Connection
                conn.end();
                // Close gulp task
                cb();
            });

            stream.pipe(process.stdout);
            stream.stderr.pipe(process.stderr);
        });
    }).on('error', function() {
        gutil.log('Error connecting to VM');
        cb();
    }).connect({
        host: '127.0.0.1',
        port: 2222,
        username: 'ubuntu',
        privateKey: require('fs').readFileSync('/Users/mathieuhays/new-www/.vagrant/machines/default/virtualbox/private_key')
    });
}


gulp.task('php:install', function( cb ) {
    var command = 'cd ' + pluginFolder + ' && ./tests/bin/install.sh tests_wp phpmyadmin phpmyadmin';
    runCommandOnVM(command, cb);
});


gulp.task('php:test', function( cb ) {
    var command = 'source ~/.profile && phpunit -c ' + path.join( pluginFolder, 'phpunit.xml' );
    runCommandOnVM(command, cb);
});


/**
 *  Main Tasks
 */
gulp.task('default', ['scss:watch']);
gulp.task('build', ['scss:build:production', 'js:build:production']);
gulp.task('lint', function() {
    runSequence('php:lint', 'scss:lint', 'js:lint');
});
