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
        dir.assets + 'style/parsley.scss',
        dir.assets + 'style/languages.scss',
        dir.npm + 'flipclock/compiled/flipclock.css',
        dir.npm + 'font-awesome/css/font-awesome.css',
        dir.assets + 'style/vendor/animate/animate.css',
        dir.npm + 'animsition/dist/css/animsition.css',
        dir.npm + 'chosen-js/chosen.css',
        dir.npm + 'fancybox/dist/css/jquery.fancybox.css',
        dir.npm + 'owl.carousel/dist/assets/owl.carousel.css',
        dir.npm + 'owl.carousel/dist/assets/owl.theme.default.css',
        dir.assets + 'style/user-profile.scss',
        dir.assets + 'style/main.scss',
        dir.assets + 'style/menu.scss',
        dir.assets + 'style/order.scss'
    ])
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(concat('style.css'))
        .pipe(gulp.dest(dir.dist + 'css'));

    gulp.src([
        dir.assets + 'style/template/template-style.css',
        dir.assets + 'style/template/responsive.css'
    ])
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(concat('template-style.css'))
        .pipe(gulp.dest(dir.dist + 'css'));

});

gulp.task('admin-sass', function () {
    gulp.src([
        dir.assets + 'style/admin.scss'
        ])
        .pipe(sass({ outputStyle: 'compressed' }).on('error', sass.logError))
        .pipe(concat('admin.css'))
        .pipe(gulp.dest(dir.dist + 'css'));
});



gulp.task('scripts', function() {
    gulp.src([
            //Third party assets
            dir.npm + 'jquery/dist/jquery.min.js',
            dir.npm + 'bootstrap-sass/assets/javascripts/bootstrap.min.js',
            dir.npm + 'jquery-parallax.js/parallax.min.js',
            dir.npm + 'parsleyjs/dist/parsley.js',
            // dir.npm + 'typed.js/lib/typed.js',
            dir.npm + 'flipclock/compiled/flipclock.js',
            dir.npm + 'animsition/dist/js/animsition.js',
            dir.npm + 'chosen-js/chosen.jquery.js',
            dir.npm + 'js-cookie/src/js.cookie.js',
            dir.npm + 'jquery-countto/jquery.countTo.js',
            dir.npm + 'fancybox/dist/js/jquery.fancybox.pack.js',
            dir.npm + 'fancybox/dist/helpers/js/jquery.fancybox-media.js',
            dir.npm + 'isotope-layout/dist/isotope.pkgd.js',
            dir.npm + 'jquery-match-height/dist/jquery.matchHeight.js',
            dir.npm + 'owl.carousel/dist/owl.carousel.js',
            dir.npm + 'jquery.scrollto/jquery.scrollTo.js',
            dir.npm + 'jquery-validation/dist/jquery.validate.js',
            dir.npm + 'waypoints/lib/jquery.waypoints.js',
            dir.npm + 'waypoints/lib/shortcuts/sticky.js',
            //plugins
            dir.assets + 'scripts/downCount/jquery.downCount.js',
            dir.assets + 'scripts/pace/pace.min.js',

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

    //copy all plugins
    gulp.src(dir.assets + 'scripts/plugins/**')
        .pipe(gulp.dest(dir.dist + 'js/plugins'));

    gulp.src(dir.assets + 'scripts/template-scripts.js')
        .pipe(concat('template-scripts.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js/'));

    gulp.src(dir.assets + 'scripts/modernizr-custom.js')
        .pipe(concat('modernizr-custom.js'))
        .pipe(uglify())
        .pipe(gulp.dest(dir.dist + 'js/'));

});

gulp.task('images', function() {
    gulp.src([
            dir.assets + 'images/**'
        ])
        .pipe(gulp.dest(dir.dist + 'images'));
});

gulp.task('fonts', function() {
    gulp.src([
        dir.npm + 'bootstrap-sass/assets/fonts/**',
        dir.npm + 'font-awesome/fonts/**'

        ])
        .pipe(gulp.dest(dir.dist + 'fonts'));
});


// watcher
gulp.task('watch', function () {
    gulp.watch(dir.assets + 'style/*.scss', ['sass']);
    gulp.watch(dir.assets + 'scripts/*', ['scripts']);
    gulp.watch(dir.assets + 'images/*', ['images']);
});


gulp.task('default', ['sass', 'admin-sass', 'scripts', 'fonts', 'images', 'watch']);
