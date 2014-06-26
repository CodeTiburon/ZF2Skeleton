ZF2Skeleton
===========
For start use frontend

1) install nodejs<br/>
2) npm install<br/>
3) bower install<br/>

<h2>Bower like Composer</h2>
<br/>
Main file: <i>bower.json</i><br/>

<h3>How to use</h3>
<br/>
>bower install <package><br/>
<br/>
<p>By default to bower.json add bootstrup,lodash.</p>

if need more js lib search in http://bower.io/search/<br/>
<br/>
<h2>GULP task manager for frontend development (http://gulpjs.com)</h2><br/>
<br/>
Main file: <i>gulpfile.js</i><br/>
<br/>
by default have 2 task
<br/>
1) compile public/js/vendor.js, public/js/vendor.min.js<br/>
2) compile public/css/vendor.css, public/css/vendor.min.css<br/>
<br/>
<h3>How to use Gulp</h3><br/>
<br/>
In console<br/>

>gulp default<br/>

<p>Clear folders public/js and public/css, run added task (by default compile vendors files), start watch added tasks (one of the task)</p>

<br/>
>gulp build<br/>

<p>Clear folders public/js and public/css, run added task (by default compile vendors files)</p>

<br/>
>gulp watch<br/>

<p>Start watch added tasks (one of the task)</p><br/>


