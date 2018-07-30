<?php include 'includes/header.php';?>
      <style>.docs-navbar-top{margin-bottom:0;border-color:#39cccc;background-color:#fff}.docs-navbar-top .navbar-nav>.active>a,.docs-navbar-top .navbar-nav>.active>a:hover,.docs-navbar-top .navbar-nav>.active>a:focus{background-color:#f7f7f7}.docs-jumbotron{background-color:#39cccc;color:#fff}.docs-demo .form-control+.form-control,.docs-demo .form-control+.row,.docs-panel .input-group+.input-group,.docs-panel .input-group+.btn{margin-top:10px}.docs-panel .docs-btn-group .btn{margin-bottom:10px}.docs-footer{min-height:50px;margin-top:40px;background-color:#39cccc}</style>
      <main class="container-fluid">
         <div class="row">
            <div class="col-sm-6">
               <h3 class="page-header">Demo:</h3>
               <div class="docs-demo">
                  <form class="form-horizontal docs-form" role="form">
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Inputs</label>
                        <div class="col-sm-10">
                           <input id="hidden" name="hidden" value="invisible" type="hidden"> <input class="form-control" id="text" name="text" type="text" placeholder="text input"> <input class="form-control" id="password" name="password" type="password" placeholder="password input">
                           <div class="row">
                              <div class="col-xs-6"><input class="form-control" id="multiple" name="multiple" type="text" placeholder="text input with the same name"></div>
                              <div class="col-xs-6"><input class="form-control" id="multiple" name="multiple" type="text" placeholder="text input with the same name"></div>
                           </div>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Radios</label>
                        <div class="col-sm-10"><label class="radio-inline"><input id="radio1" name="radio" type="radio"> Radio 1</label><label class="radio-inline"><input id="radio2" name="radio" type="radio"> Radio 2</label><label class="radio-inline"><input id="radio3" name="radio" type="radio"> Radio 3</label></div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Checkboxes</label>
                        <div class="col-sm-10"><label class="checkbox-inline"><input id="checkbox1" name="checkbox" type="checkbox"> Checkbox 1</label><label class="checkbox-inline"><input id="checkbox2" name="checkbox" type="checkbox"> Checkbox 2</label><label class="checkbox-inline"><input id="checkbox3" name="checkbox" type="checkbox"> Checkbox 3</label></div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Selects</label>
                        <div class="col-sm-10">
                           <select class="form-control" id="select1" name="select1">
                              <option value="1">Option 1</option>
                              <option value="2">Option 2</option>
                              <option value="3">Option 3</option>
                           </select>
                           <select class="form-control" id="select2" name="select2" multiple="multiple">
                              <option value="1">Option 1</option>
                              <option value="2">Option 2</option>
                              <option value="3">Option 3</option>
                           </select>
                        </div>
                     </div>
                     <div class="form-group">
                        <label class="col-sm-2 control-label">Textarea</label>
                        <div class="col-sm-10"><textarea class="form-control" id="textarea" name="textarea" placeholder="textarea"></textarea></div>
                     </div>
                     <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-10"><button class="btn btn-default" type="submit">Submit</button> <button class="btn btn-default" type="reset">Reset</button> <a class="btn btn-default" data-toggle="tooltip" href="javascript:window.location.reload();" title="Reload to check the cache">Reload</a></div>
                     </div>
                  </form>
               </div>
            </div>
            <div class="col-sm-6">
               <h3 class="page-header">Panel:</h3>
               <div class="docs-panel">
                  <h4>Options</h4>
                  <div class="checkbox"><label><input data-option="local" type="checkbox" value="true" checked="checked"> local (Cache in localStorage)</label></div>
                  <div class="checkbox"><label><input data-option="session" type="checkbox" value="true" checked="checked"> session (Cache in sessionStorage)</label></div>
                  <div class="input-group"><span class="input-group-addon">key</span> <input class="form-control" data-option="key" type="text"></div>
                  <div class="input-group"><span class="input-group-addon">controls</span> <input class="form-control" data-option="controls" type="text" value="[&quot;select&quot;,&quot;textarea&quot;,&quot;input&quot;]"></div>
                  <br>
                  <h4>Methods</h4>
                  <div class="docs-btn-group"><button class="btn btn-primary" data-method="outputCache" type="button">Output Cache</button> <button class="btn btn-primary" data-method="removeCache" type="button">Remove Cache</button> <button class="btn btn-primary" data-method="removeCaches" type="button">Remove Caches</button> <button class="btn btn-success" data-method="store" type="button">Store</button> <button class="btn btn-warning" data-method="clear" type="button">Clear</button> <button class="btn btn-danger" data-method="destroy" type="button">Destroy</button></div>
                  <div class="input-group"><span class="input-group-btn"><button class="btn btn-info" data-method="getCache" data-output="#getCacheInto" type="button">Get Cache</button></span> <input class="form-control" id="getCacheInto" type="text"></div>
                  <div class="input-group"><span class="input-group-btn"><button class="btn btn-info" data-method="getCaches" data-output="#getCachesInto" type="button">Get Caches</button></span> <input class="form-control" id="getCachesInto" type="text"></div>
                  <div class="input-group"><span class="input-group-btn"><button class="btn btn-primary" data-input="#setCacheWith" data-method="setCache" type="button">Set Cache</button></span> <input class="form-control" id="setCacheWith" type="text" value="{}"></div>
                  <div class="input-group"><span class="input-group-btn"><button class="btn btn-primary" data-input="#setCachesWith" data-method="setCaches" type="button">Set Caches</button></span> <input class="form-control" id="setCachesWith" type="text" value="[]"></div>
               </div>
            </div>
         </div>
      </main>
      <footer class="docs-footer"></footer>
	<script src="//code.jquery.com/jquery-1.11.1.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js"></script>
	<script src="js/formcache.min.js"></script>
	<script>$(function(){var t=$(".docs-form"),o=$(".docs-panel"),a={};t.formcache(),$('[data-toggle="tooltip"]').tooltip(),o.find("[data-option]").on("change",function(){var o=$(this),c=o.data("option");c&&(a[c]="checkbox"===this.type?this.checked:JSON.parse(o.val()),t.formcache("destroy").formcache(a))}),o.on("click","[data-method]",function(){var o,a=$(this).data();a.input&&(a.option=JSON.parse($(a.input).val())),o=t.formcache(a.method,a.option),a.output&&$(a.output).val(JSON.stringify(o))})});</script>
 <?php include 'includes/footerx.php';?>


