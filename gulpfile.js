const gulp = require('gulp');
const cleanCSS = require('gulp-clean-css'); 
const minifyJS = require('gulp-minify');
const imagemin = require('gulp-imagemin');

function styles() {
    console.log('styles run');
}

function scripts() {
    
}

function minifyCss() {
    return gulp.src('src/css/style.css')
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('dist/css'))

}

function minifyJs(){
        return gulp.src(['src/js/*.js'])
          .pipe(minifyJS())
          .pipe(gulp.dest('dist/js'))
      
}

 
function imageminify(){
    gulp.src('src/img/*')
    .pipe(imagemin({ progerssive:true }))
    .pipe(gulp.dest('dist/img'))
}



gulp.task('styles', styles);
gulp.task('scripts', scripts);
gulp.task('minifycss', minifyCss);
gulp.task('minifyjs', minifyJs);
gulp.task('imagemin', imageminify);