var gulp = require('gulp');
var util = require('gulp-util');

var sourcemaps = require('gulp-sourcemaps');
var source = require('vinyl-source-stream');
var buffer = require('vinyl-buffer');

var browserify = require('browserify');
var watchify = require('watchify');
var templateify = require('browserify-compile-templates').configure({ noVar: true, noUnderscore: true });
var babelify = require('babelify');
var uglify = require('gulp-uglify');
var sass = require('gulp-sass');
var autoprefix = require('gulp-autoprefixer');
var notify = require("gulp-notify");

var stringify = require('stringify');
var hbsfy = require('hbsfy');

var config = {
	bootstrapDir: './node_modules/bootstrap-sass',
	publicDir: './dist',
};

var bundler;
var libsBundler;

function standardHandler(err) {
	console.log("Error: " + err.message);
	notify("Build Error:" + err.message);
}

function browserifyHandler(err) {
	standardHandler(err);
	this.emit('end');
}

gulp.task('application', function () {
	bundler = bundler || watchify(browserify({
		entries: './src/app.js',
		debug: true,
		cache: {},
		packageCache: {},
		})
		.transform(templateify)
		.transform(babelify)
		.transform(hbsfy)
		.transform(stringify, {
			appliesTo: { includeExtensions: ['.tpl', '.html'] }
		})
	);

	return bundler
		.bundle()
		.on('error', browserifyHandler)
		.pipe(source('application.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({ loadMaps: true, debug: true }))
		// .pipe(uglify())
		.pipe(sourcemaps.write('./', {
			includeContent: false,
			sourceRoot: '../'
		}))
		.pipe(gulp.dest('dist'));
});

gulp.task('libraries', function () {
	libsBundler = libsBundler || watchify(browserify({
		entries: './src/libs.js',
		debug: true,
		cache: {},
		packageCache: {},
		})
		.transform(templateify)
		.transform(babelify)
		);

	return libsBundler
		.bundle()
		.on('error', browserifyHandler)
		.pipe(source('libraries.js'))
		.pipe(buffer())
		.pipe(sourcemaps.init({ loadMaps: true, debug: true }))
		.pipe(uglify())
		.pipe(sourcemaps.write('./', {
			includeContent: false,
			sourceRoot: '../'
	}))
		.pipe(gulp.dest('dist'));
});

gulp.task('html', function () {
	return gulp.src('src/*.html')
		.pipe(gulp.dest('dist'));
});

gulp.task('css', function () {
	return gulp.src('src/css/style.css')
		.pipe(autoprefix('last 2 version'))
		.pipe(gulp.dest(config.publicDir + '/css'));
});

gulp.task('scss', function () {
	return gulp.src('src/css/app.scss')
		.pipe(sass({
			style: 'compressed',
			includePaths: [config.bootstrapDir + '/assets/stylesheets'],
		}))
		.on("error", notify.onError(function (error) {
			return "Sass Error: " + error.message;
		}))
		.pipe(autoprefix('last 2 version'))
		.pipe(gulp.dest(config.publicDir + '/css'));
});

gulp.task('fonts', function () {
	return gulp.src(config.bootstrapDir + '/assets/fonts/**/*')
		.pipe(gulp.dest(config.publicDir + '/fonts'));
});


//////////////////////////////////////////////////////////////
// CREATE A DEFAULT TASK /////////////////////////////////////
//////////////////////////////////////////////////////////////

gulp.task('watch', function () {
	gulp.watch(['src/**/*.js', '!src/Libs.js', 'src/**/*.html', 'src/**/*.hbs'], ["application"]);
	gulp.watch('src/Libs.js', ["libraries"]);
	gulp.watch('src/css/*.scss', ["scss"]);
	gulp.watch('src/**/*.css', ["css"]);
	gulp.watch('src/*.html', ["html"]);
});

gulp.task('default', ['html', 'scss', 'fonts', 'css', 'libraries', 'application', 'watch']);
