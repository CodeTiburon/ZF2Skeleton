var gulp = require('gulp'),

    gutil = require('gulp-util'),
    concat = require('gulp-concat'),
    uglify = require('gulp-uglify'),
    clean = require('gulp-clean'),
    path = require('path'),
    rename = require('gulp-rename'),
    minifyCSS = require('gulp-minify-css');

var paths = {
    'scripts': {
        'vendor': [
            'bower_components/lodash/dist/lodash.min.js',
            'bower_components/jquery/dist/jquery.min.js',
            'bower_components/bootstrap/dist/js/bootstrap.min.js'
        ]
    },
    style: {
        'vendor': [
            'bower_components/bootstrap/dist/css/bootstrap.min.css'
        ]
    }
};

gulp.task('style.vendor', function () {
    return gulp.src(paths.style.vendor)
        .pipe(concat('vendor.css'))
        .pipe(gulp.dest('public/css/'))
        .pipe(rename('vendor.min.css'))
        .pipe(minifyCSS({keepBreaks: true}))
        .pipe(gulp.dest('public/css/'));
});

gulp.task('scripts.vendor', function () {
    return gulp.src(paths.scripts.vendor)
        .pipe(concat('vendor.js'))
        .pipe(gulp.dest('public/js/'))
        .pipe(rename('vendor.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('public/js/'));
});

// Rerun the task when a file changes
gulp.task('watch', function () {
    gulp.watch(paths.scripts.vendor, ['scripts.vendor']);

    gulp.watch(paths.style.vendor, ['style.vendor']);
});

gulp.task('clean', function () {
    gulp.src(['public/js/**', 'public/css/**'], {read: false})
        .pipe(clean());
});

gulp.task('build', ['clean'], function () {
    gulp.start(
        'scripts.vendor',

        'style.vendor'
    );
});

// The default task (called when you run `gulp` from cli)
gulp.task('default', [
        'scripts.vendor',

        'watch',

        'style.vendor'
    ]
);