var gulp = require('gulp'),
    sass = require('gulp-sass'),
    checkPages = require("check-pages"),
    shell = require('gulp-shell'),
    uglify = require('gulp-uglify'),
    watch = require('gulp-watch')
    plumber = require('gulp-plumber');

gulp.task('styles', function () {
    gulp.src('resources/assets/sass/*.scss')
        .pipe(plumber())
        .pipe(sass())
        .pipe(gulp.dest('public/css'));
});

gulp.task('scripts', function() {
  gulp.src('resources/assets/js/*.js')
    .pipe(plumber())
    .pipe(uglify())
    .pipe(gulp.dest('public/js'))
});

gulp.task('serve', shell.task('php artisan serve'))
gulp.task("checkDev", [ "serve" ], function(callback) {
  var options = {
    pageUrls: [
      'http://localhost:8000/',
    ],
    checkLinks: true,
    onlySameDomain: true,
    queryHashes: true,
    noRedirects: true,
    noLocalLinks: true,
    linksToIgnore: [
      'http://localhost:8000/404.html'
    ],
    checkXhtml: true,
    checkCaching: true,
    checkCompression: true,
    maxResponseTime: 200,
    userAgent: 'custom-user-agent/1.2.3',
    summary: true
  };
  checkPages(console, options, callback);
});

gulp.task("checkProd", function(callback) {
  var options = {
    pageUrls: [
      'http://example.com/',
      'http://example.com/blog',
      'http://example.com/about.html'
    ],
    checkLinks: true,
    maxResponseTime: 500
  };
  checkPages(console, options, callback);
});

gulp.task('watch', function() {
  gulp.watch('resources/assets/sass/*.scss', ['styles']);
  gulp.watch('resources/assets/js/*.js', ['scripts']);
});

gulp.task("default", ["styles","scripts"]);
