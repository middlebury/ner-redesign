var gulp = require('gulp');
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

// browser-sync task for starting the server.
gulp.task('browser-sync', function() {
    //watch files
    var files = [
        './style.css',
        './js/*.js',
        './*.php'
    ];
 
    //initialize browsersync
    browserSync.init(files, {
    //browsersync with a php server
        proxy: 'localhost:8888/NER/',
        notify: false,
        open: false
    });
});

// Sass task, will run when any SCSS files change & BrowserSync
// will auto-update browsers
gulp.task('sass', function() {
    return gulp.src('./assets/sass/*.scss')
        .pipe(plumber({
            errorHandler: function(error) {
                console.log(error.message);
                this.emit('end');
            }
        }))
        .pipe(sourcemaps.init())
        .pipe(sass())
        .pipe(autoprefixer('last 2 versions'))
        .pipe(sourcemaps.write('./'))
        .pipe(gulp.dest('./'))
        // .pipe(cmq())
        // .pipe(rename({suffix: '.min'}))
        // .pipe(minifyCss())
        // .pipe(gulp.dest('./'))
        .pipe(reload({ stream:true }));
});

gulp.task('js', function() {
    return gulp.src('./assets/js/**/*.js')
        .pipe(sourcemaps.init())
        .pipe(gulp.dest('./js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(uglify())
        .pipe(gulp.dest('./js'))
        .pipe(sourcemaps.write('./maps'))
        .pipe(reload({ stream: true }))
});
 
// Default task to be run with `gulp`
gulp.task('default', ['sass', 'js', 'browser-sync'], function() {
    gulp.watch("sass/**/*.scss", ['sass']);
    gulp.watch("assets/js/**/*.js", ['js']);
});