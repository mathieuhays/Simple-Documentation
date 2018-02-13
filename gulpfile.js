/**
 * Gulp config
 */

// Dependencies
const PATH = require('path');
const GULP = require('gulp');
const WATCH = require('gulp-watch');
const SOURCEMAPS = require('gulp-sourcemaps');
const SASS = require('gulp-sass');
const POSTCSS = require('gulp-postcss');
const AUTOPREFIXER = require('autoprefixer');
const RENAME = require('gulp-rename');
const GUTIL = require('gulp-util');
const UGLIFY = require('gulp-uglify');
const CONCAT = require('gulp-concat');


// Paths
const ASSETS = PATH.join(__dirname, 'assets');
const STYLESHEET_SRC = PATH.join(ASSETS, 'src', 'scss');
const STYLESHEET_DIST = PATH.join(ASSETS, 'dist', 'css');
const JAVASCRIPT_SRC = PATH.join(ASSETS, 'src', 'js');
const JAVASCRIPT_DIST = PATH.join(ASSETS, 'dist', 'js');


// Patterns
const STYLESHEET_ALL = PATH.join(STYLESHEET_SRC, '**', '*.scss');
const JAVASCRIPT_ADMIN = PATH.join(JAVASCRIPT_SRC, 'admin', '*.js');


// Helper
function excludeUnderscored(initialPath) {
	return '!' + initialPath.replace(/(\*\.\w+)$/g, '_$1');
}


// Tasks
GULP.task('js:build:development', () => {
	GULP.src([ JAVASCRIPT_ADMIN, excludeUnderscored( JAVASCRIPT_ADMIN ) ])
		.pipe(CONCAT('admin.js'))
		.pipe(GULP.dest(JAVASCRIPT_DIST))
});


GULP.task('js:build:production', [ 'js:build:development' ], () => {
	GULP.src([ JAVASCRIPT_ADMIN, excludeUnderscored( JAVASCRIPT_ADMIN ) ])
		.pipe(CONCAT('admin.js'))
		.pipe(UGLIFY().on('error', GUTIL.log))
		.pipe(RENAME({ suffix: '.min' }))
		.pipe(GULP.dest(JAVASCRIPT_DIST))
});


GULP.task('js:watch', ['js:build:development'], () => {
	return WATCH([ JAVASCRIPT_ADMIN ], () => {
		GULP.start('js:build:development')
	})
});


GULP.task('scss:build:development', () => {
	GULP.src([ STYLESHEET_ALL, excludeUnderscored(STYLESHEET_ALL) ])
		.pipe(SOURCEMAPS.init())
		.pipe(SASS().on('error', GUTIL.log))
		.pipe(POSTCSS([ AUTOPREFIXER({ browsers: ['> 5%', 'IE 9'] }) ]))
		.pipe(SOURCEMAPS.write())
		.pipe(GULP.dest(STYLESHEET_DIST));
});


GULP.task('scss:build:production', ['scss:build:development'], () => {
	GULP.src([ STYLESHEET_ALL, excludeUnderscored(STYLESHEET_ALL) ])
		.pipe(SASS({ outputStyle: 'compressed' }).on('error', GUTIL.log))
		.pipe(POSTCSS([ AUTOPREFIXER({ browsers: ['> 5%', 'IE 9'] }) ]))
		.pipe(RENAME({ suffix: '.min' }))
		.pipe(GULP.dest(STYLESHEET_DIST))
});


GULP.task('scss:watch', ['scss:build:development'], () => {
	WATCH(STYLESHEET_ALL, () => {
		GULP.start('scss:build:development')
	})
});


// Main commands
GULP.task('watch', () => {
	GULP.start('scss:watch');
	GULP.start('js:watch');
});

GULP.task('default', ['watch']);

GULP.task('build', ['scss:build:production', 'js:build:production']);
