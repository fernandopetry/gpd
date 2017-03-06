const gulp = require('gulp');
const browserSync = require('browser-sync').create();
const del = require('del');
const shell = require('gulp-shell');

gulp.task('browser-sync',function(){
    browserSync.init({
        proxy: "localhost:8080",
        files: [
            "config/**/*.php",
            "public/**/*.php",
            "src/**/*.php",
            "*.php",
            "public/assets/**/*.css",
            "public/assets/**/*.js",
            "templates/**/*.twig"
        ]
    });
});

gulp.task('shell', shell.task([
  'php -S localhost:8080 -t public'
]));

gulp.task('npmpublic',function(){
    gulp.src([
        './node_modules/sweetalert/dist/sweetalert.min.js'
    ]).pipe(gulp.dest('./public/assets/npm/sweetalert'));
    gulp.src([
        './node_modules/sweetalert/dist/sweetalert.css'
    ]).pipe(gulp.dest('./public/assets/npm/sweetalert'));
});

gulp.task('watch',function(){
    gulp.watch(['./public/**/*.less'],['./source/**/*.php']);
});

gulp.task('default',['shell','watch','browser-sync']);
