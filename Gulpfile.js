'use strict';

var gulp    = require('gulp');
var sass    = require('gulp-sass');
var concat  = require('gulp-concat');
var uglify  = require('gulp-uglify');
var watch   = require('gulp-watch');

var dir = {
    assets: './src/AppBundle/Resources/',
    dist: './web/',
    npm: './node_modules/'
};

gulp.task('sass', function() {
    gulp.src([
        dir.assets + 'style/main.scss',
        dir.assets + 'style/user-profile.scss',
        dir.assets + 'style/parsley.scss',
        dir.assets + 'style/languages.scss'
        ])
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(concat('style.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});



gulp.task('scripts', function() {
    gulp.src([
            //Third party assets
            dir.npm + 'jquery/dist/jquery.min.js',
            dir.npm + 'bootstrap-sass/assets/javascripts/bootstrap.min.js',
            dir.npm + 'jquery-parallax.js/parallax.min.js',
            dir.npm + 'parsleyjs/dist/parsley.js',
            dir.npm + 'typed.js/lib/typed.js',
            // Main JS file
            dir.assets + 'scripts/main.js'
        ])
        .pipe(concat('script.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js'));

    // parsleyjs validation locales LT and EN
    gulp.src(dir.npm + 'parsleyjs/dist/i18n/lt.js')
        .pipe(concat('lt.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js/pasleyjs-locale'));
    gulp.src(dir.npm + 'parsleyjs/dist/i18n/en.js')
        .pipe(concat('en.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js/pasleyjs-locale'));

});

gulp.task('images', function() {
    gulp.src([
            dir.assets + 'images/**'
        ])
        .pipe(gulp.dest(dir.dist + 'images'));
});

gulp.task('fonts', function() {
    gulp.src([
        dir.npm + 'bootstrap-sass/assets/fonts/**'
        ])
        .pipe(gulp.dest(dir.dist + 'fonts'));
});


// watcher
gulp.task('watch', function () {
    gulp.watch(dir.assets + 'style/*.scss', ['sass']);
    gulp.watch(dir.assets + 'scripts/*', ['scripts']);
    gulp.watch(dir.assets + 'images/*', ['images']);
});


gulp.task('default', ['sass', 'scripts', 'fonts', 'images', 'watch']);
