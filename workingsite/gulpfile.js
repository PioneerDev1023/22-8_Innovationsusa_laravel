var gulp = require('gulp');
var sass = require('gulp-sass');
var cleancss = require('gulp-clean-css');
var rename = require('gulp-rename');
var autoprefixer = require('gulp-autoprefixer');
var sourcemaps = require('gulp-sourcemaps');

// configure the paths
var watch_dir = './resources/sass/**/*.scss';
var src_dir = './resources/sass/*.scss';
var dest_dir = './public/css';

var paths = {
    source: src_dir
};

gulp.task('watch', function() {
  gulp.watch(watch_dir, gulp.series('build'));
});

gulp.task('build', function() {
  return gulp.src(paths.source)
    .pipe(sourcemaps.init())
    .pipe(sass({outputStyle: 'compact', precision: 10})
      .on('error', sass.logError)
    )
    .pipe(sourcemaps.write({includeContent: false}))
    .pipe(autoprefixer())       
    .pipe(gulp.dest(dest_dir))
    .pipe(cleancss())
    .pipe(rename({
      suffix: '.min',
    }))
    .pipe(gulp.dest(dest_dir));
});

gulp.task('default', gulp.series('build'));
