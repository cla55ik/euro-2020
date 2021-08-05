
/*
const lr = require('tiny-lr');
const gulp = require('gulp');
const cleanCSS = require('gulp-clean-css'); 
const minifyJS = require('gulp-minify');
const imagemin = require('gulp-imagemin');
const livereload = require('gulp-livereload');
const connect = require('connect');
var server = lr();
*/
let proj_folder = "dist";
let source_folder = "src";
let path = {
    build:{
        html:proj_folder + "/",
        php: proj_folder + "/",
        css: proj_folder + "/css/",
        js: proj_folder + "/js/",
        img: proj_folder + "/img/",
        fonts: proj_folder + "/fonts/"
    },
    src:{
        html: "*.html",
        php: "*.php",
        css: source_folder + "/css/style.css",
        js: source_folder + "/js/*.js",
        img: source_folder + "/img/**/*.{jpg, svg, png, gif, ico, webp}",
        fonts: source_folder + "/fonts/*.ttf"
    },
    watch:{
        html: source_folder + "/**/*.html",
        php: source_folder + "/**/*.php",
        css: source_folder + "/css/**/*.css",
        js: source_folder + "/js/**/*.js",
        img: source_folder + "/img/**/*.{jpg, svg, png, gif, ico, webp}",
    },
    clean: "./" + proj_folder + "/"
};

let {src, dest} = require('gulp');

let gulp = require('gulp');
let browsersync = require('browser-sync').create();


function browserSync() {
    browsersync.init({
        server:{
            baseDir: "./" + proj_folder + "/",
            port: 3000,
            notify: false
        }
    });
}    

function html() {
    return src(path.src.html)
        .pipe(dest(path.build.html))
        .pipe(browsersync.stream());
}

function php() {
    return src(path.src.php)
        .pipe(dest(path.build.php))
        .pipe(browsersync.stream());
}

let build = gulp.series(html, php);
let watch = gulp.parallel(build,browserSync);

exports.php = php;
exports.html = html;
exports.build = build;
exports.watch = watch;
exports.default = watch;


/*    #####   */
/*
function styles() {
    console.log('styles run');
}

function scripts() {

}

function minifyCss() {
    return gulp.src('src/css/style.css')
        .pipe(cleanCSS({compatibility: 'ie8'}))
        .pipe(gulp.dest('dist/css'))
        .pipe(livereload(server)); // даем команду на перезагрузку страницы
}

function minifyJs(){
        return gulp.src(['src/js/*.js'])
          .pipe(minifyJS())
          .pipe(gulp.dest('dist/js'))
          .pipe(livereload(server)); // даем команду на перезагрузку страницы
}

 
function imageminify(){
    gulp.src('src/img/*')
    .pipe(imagemin({ progerssive:true }))
    .pipe(gulp.dest('dist/img'))
    
}

gulp.task('http-server', function() {
    connect()
        .use(require('livereload')())
        .use(connect.static('./public'))
        .listen('9000');

    console.log('Server listening on http://localhost:9000');
});



gulp.task('styles', styles);
gulp.task('scripts', scripts);
gulp.task('minifycss', minifyCss);
gulp.task('minifyjs', minifyJs);
gulp.task('imagemin', imageminify);

gulp.task('watch', gulp.parallel('minifycss', 'minifyjs', 'http-server'));

*/