var gulp = require('gulp');
var sass = require('gulp-sass');
var checkPages = require("check-pages");
var shell = require('gulp-shell');
var uglify = require('gulp-uglify');

gulp.task('sass', function () {
    gulp.src('resources/assets/sass/*.scss')
        .pipe(sass())
        .pipe(gulp.dest('public/css'));
});

gulp.task('js', function() {
  gulp.src('resources/assets/js/*.js')
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

gulp.task("default", ["sass","js"]);
