ZF2Skeleton
===========
For start use frontend

1) install nodejs<br/>
2) npm install<br/>
3) bower install<br/>

<h2>Bower - package manager for the web</h2>
<br/>
Main file: <i>bower.json</i><br/>
<p>By default bower create folder with depend. /bower_components, if want change this directory create .bowerrc file in root directory and add next content:</p>
<p>
{<br/>
  "directory": "public/bower_components"<br/>
}<br/>
</p><br/>

<h3>How to use</h3><br/>

>bower install package-name<br/>

<br/>
<p>By default to bower.json add bootstrup,lodash.</p>

if need more js lib search in http://bower.io/search/<br/>
<br/>
<h2>GULP - streaming build system (http://gulpjs.com)</h2><br/>
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

<p>Start watch added tasks</p><br/>


