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
    scsslint = require('gulp-scss-lint'),
    eslint = require('gulp-eslint'),
    phpcs = require('gulp-phpcs'),
    runSequence = require('run-sequence');


// Folders
const ROOT = __dirname;
const ASSETS = path.join(ROOT, 'assets');
const JS = path.join(ASSETS, 'js');
const CSS = path.join(ASSETS, 'css');
const SCSS = path.join(ASSETS, 'scss');

// Globs
const ALL_SCSS_FILES = [ path.join(SCSS, '**/*.scss'), '!' + path.join(SCSS, 'bower_components/**/*.*') ];
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
        // .pipe(postcss([ autoprefixer({ browsers: ['> 5%', 'IE 9'] }) ]))
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


/**
 *  Main Tasks
 */
gulp.task('default', ['scss:watch']);
gulp.task('build', ['scss:build:production', 'js:build:production']);
gulp.task('lint', function() {
    runSequence('php:lint', 'scss:lint', 'js:lint');
});
