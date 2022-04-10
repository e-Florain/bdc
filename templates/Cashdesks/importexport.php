<h4>Import<h4>
<div class="row">
    <form id="importasso" class="col s12" action="/associations/import" method="post" enctype="multipart/form-data">
    <div class="row">
        <div class="input-field file-field col s6">
        <div class="btn">
            <span>Pièce jointe</span>
            <input name="uploadfile" id="uploadfile" type="file">
        </div>
        <div class="file-path-wrapper">
            <input class="file-path validate">
        </div>
        </div>
    </div>
    <div class="col offset-s1">
        <input class="btn waves-effect waves-light" type="submit" value="Importer" name="submit">
    </div>
    </form>
    <!--
    <div class="col offset-s1">
        
        <!--<button class="btn waves-effect waves-light">Importer
        <i class="material-icons right">send</i>
        </button>-->
        <!--<a class="btn waves-effect waves-light"><i class="material-icons right">send</i>Importer</a>-->
    <!--</div>-->
</div>

<h4>Export<h4>
<div class="row">
    <div class="col offset-s1">
        <a class="btn waves-effect waves-light" href="/associations/export"><i class="material-icons right">send</i>Exporter</a>
    </div>
</div>