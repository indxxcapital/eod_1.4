{include file="notice.tpl"}


<div class="row-fluid">
                    <div class="span22">
                        <div class="box box-magenta">
                            <div class="box-title">
                                <h3><i class="icon-reorder"></i>Add New Security for index {$sessData.NewIndxxName}</h3>
                                <div class="box-tool">
                                    <a href="{$BASE_URL}index.php?module=casecurities&event=uploadSecuritiesforRunning">Upload Securities</a>
                                <!--    <a href="#" data-action="collapse"><i class="icon-chevron-up"></i></a>
                                    <a href="#" data-action="close"><i class="icon-remove"></i></a>-->
                                </div>
                            </div>
                            <form class="form-wizard" method="post"> 
                            <div class="box-content done"  id="p_scents">
                                
                               {foreach from=$fields key=p item=item}
             {if $p%12==0}  <div class="controls step controls-row">
                                    <span class="formnumber">{$p/12+1}</span>
            {/if}                         {field data=$item value=$postData}{/field}
                                      
                {if $p%12==11}  </div>{/if}                    
                 {/foreach}    
                            </div>
                            
                            <div class="box-content" >
                            <div class="form-actions">
                                        <button class="btn btn-primary"  id="addScnt" type="submit"><i class="icon-plus"></i> Add More</button>
                                          <button class="btn btn-primary" name='submit' value="submit" type="submit"><i class="icon-ok"></i> Save</button>
                                       <button class="btn" type="button">CanceL</button>
                                    </div>
                            </div>
                          <!--  <h2><a href="#" id="addScnt">Add Another Input Box</a></h2>-->
                        <input type="hidden" id="totalfields" name="totalfields" value="{$totalfields}" />
                        </form>
                        </div>
                    </div>
                </div>
                
                
                
                
{literal}
<script type="text/javascript">

$(function() {
        var scntDiv = $('#p_scents');
        var i = $('#p_scents div').size() + 1;
        $('#addScnt').click( function() {
             $('<div class="controls step  controls-row"> <span class="formnumber">'+i+'</span><input type="text" placeholder="Security Name" class="span1" name="name['+i+']"><input type="text" name="isin['+i+']" placeholder="ISIN" class="span1"><input  name="ticker['+i+']" type="text" placeholder="Ticker" class="span1"><input name="curr['+i+']" type="text" placeholder="Currency" class="span1"><input type="text" placeholder="Div Curr"  name="divcurr['+i+']" class="span1"><input name="sedol['+i+']" type="text" placeholder="Sedol" class="span1"><input name="cusip['+i+']" type="text" placeholder="Cusip" class="span1"><input name="countryname['+i+']" type="text" placeholder="Country Name" class="span1"><input name="sector['+i+']" type="text" placeholder="Sector" class="span1"><input name="industry['+i+']" type="text" placeholder="Inustry" class="span1"><input name="subindustry['+i+']" type="text" placeholder="SubInustry" class="span1">').appendTo(scntDiv);
                $('#totalfields').val(i);
				i++;
                return false;
        });

});
</script>
{/literal}                