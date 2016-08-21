'use strict';
var gulp = require('gulp'),
    util = require('gulp-util'),
    less = require('gulp-less'),
    concat = require('gulp-concat'),
    cleanCSS = require('gulp-clean-css'),
    rename = require('gulp-rename'),
    watch = require('gulp-watch'),

    vendor = 'node_modules',
    lessPaths = [
        vendor + "/bootstrap-less/bootstrap",
        vendor + "/font-awesome/less"
    ],
    jsPaths = [
        vendor + "/jquery/dist/jquery.min.js",
        vendor + "/bootstrap-less/js/bootstrap.min.js"
    ],
    fontPaths = [
        vendor + '/font-awesome/fonts/**/*.{ttf,woff,woff2,eof,svg}',
        vendor + '/bootstrap-less/fonts/**/*.{ttf,woff,woff2,eof,svg}'
    ];

/**
 * Compiles our JS files to our web folder.
 **/
gulp.task('compile-js', function () {
    return gulp.src(jsPaths)
        .pipe(concat('app.js'))
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('./web/js'));
});

/**
 * Compiles our LESS files to our web folder.
 **/
gulp.task('compile-less', function () {
    return gulp.src('./src/resources/less/style.less')
        .pipe(less({
            paths: lessPaths
        }))
        .pipe(cleanCSS())
        .pipe(rename({suffix: '.min'}))
        .pipe(gulp.dest('web/css'));
});

/**
 * Copies our font files to the public folder.
 **/
gulp.task('copy-fonts', function () {
    gulp.src(fontPaths)
        .pipe(gulp.dest('./web/fonts'));
});


gulp.task('default', ['compile-less', 'compile-js', 'copy-fonts']);
