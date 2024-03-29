var gulp = require('gulp');
var gutil = require('gulp-util');
var browserSync = require('browser-sync');
var reload = browserSync.reload;
var sass = require('gulp-sass');
var sourcemaps = require('gulp-sourcemaps');
var cmq = require('gulp-combine-mq');
var autoprefixer = require('gulp-autoprefixer');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename');
var minifyCss = require('gulp-minify-css');
var plumber = require('gulp-plumber');
var size = require('gulp-size');
var imagemin = require('gulp-imagemin');
var del = require('del');

gulp.task('browser-sync', function() {
    var files = [
        './style.css',
        './js/*.js',
        './*.php'
    ];

    browserSync.init(files, {
        proxy: 'localhost:8888/NER/',
        notify: false,
        open: false
    });
});

gulp.task('clean', function(cb) {
    del([
        './js/*',
        './style.css',
        './style.css.map',
        './images/*'
    ], cb);
});

gulp.task('images', function() {
    return gulp.src('./assets/img/*')
        .pipe(imagemin())
        .pipe(gulp.dest('./images'));
});

gulp.task('sass', function() {
    return gulp.src('./assets/sass/**/*.scss')
        .pipe(plumber({
            errorHandler: function(error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoprefixer('last 2 versions'))
        .pipe(gutil.env.production ? cmq() : gutil.noop())
        .pipe(gutil.env.production ? minifyCss() : gutil.noop())
        .pipe(gutil.env.type !== 'production' ? sourcemaps.write('./') : gutil.noop())
        .pipe(size({ showFiles: true }))
        .pipe(gulp.dest('./'))
        .pipe(reload({ stream: true }));
});

gulp.task('js', function() {
    return gulp.src('./assets/js/**/*.js')
        .pipe(sourcemaps.init())
        .pipe(gutil.env.production ? uglify() : gutil.noop())
        .pipe(!gutil.env.production ? sourcemaps.write('./') : gutil.noop())
        .pipe(size({ showFiles: true }))
        .pipe(gulp.dest('./js'))
        .pipe(reload({ stream: true }))
});

gulp.task('default', ['clean', 'images', 'sass', 'js', 'browser-sync'], function() {
    gulp.watch('./assets/sass/**/*.scss', ['sass']);
    gulp.watch('./assets/js/**/*.js', ['js']);
    gulp.watch('./assets/img/*', ['images']);
});
