<div class="ui active tab segment" data-tab="home">


	<h2 class="ui dividing header">
		<i class="globe icon" ></i>
		<div class="content">
			VF URL SHORTENED
		</div>
	</h2>	
	<div class="ui form">
		<div class="field url">
			<label><h4><i class="linkify icon"></i> MASUKAN URL TARGET</h4></label>
			<input type="text" name="url" id="url" onkeyup="proses_url(event);">
		</div>

		<button class="ui button" type="button" id="yesSubmit" onclick="proses_url(event,'submit');" >Submit</button>
	</div>

	<div class="ui divider"></div>

	<div id="notif">
		
	</div>


</div>

<div class="ui tab segment" data-tab="list">
	<div class="ui relaxed divided list" id="list_url">

	</div>
</div>

<script type="text/javascript">
var numRow=0;
var host = "<?php echo $host; ?>";

$('.menu .item').tab();

function proses_url(evt, act=""){
	var url_original=$('input[name=url]').val();
	

	if(evt['key']=='Enter' || act=='submit')
	{
		if(url_original!='' && urlValidator(url_original))
		{
			$('.url').removeClass('error');
			show_loader();

			var short_url = simpleUIDGenerator();
			var token = '<?php echo $token; ?>';
			var event_uid = '<?php echo $event_uid; ?>';

			var dataUrl = {'short_url':short_url, 'origin_url':url_original, 'token':token, 'event_uid':event_uid};

			//console.log(dataUrl);
			$.ajax({
				type: "POST",
				url: "<?php echo $BASE.'/'; ?>api/add-url/<?php echo $token; ?>",
				data: dataUrl, 
				cache: false,

				success: function(msg){
					var psn=JSON.parse(msg);
					if(psn.success==false)
					{
						if(psn.problem){
							alert(psn.problem);
						}else{
							alert(psn.msg);
						}
					}
					else{

						hide_loader();
						$('input[name=url]').val('');
						tambah_notif('positive','BERHASIL!!','URL ANDA SIAP <a href="<?php echo $BASE."/"; ?>'+short_url+'" target="_blank" rel="external nofollow">http://'+host+'<?php echo $BASE."/"; ?>'+short_url+'</a>');
						get_list_url();
					}

				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert('Terjadi Kesalahan => '+xhr.status+' : '+thrownError);        
				}
			});			
		}
		else if(url_original!='' && !urlValidator(url_original))
		{
			$('.url').removeClass('error');
			$('.url').addClass('error'); 
			tambah_notif('negative','MAAF..','FORMAT URL TERSEBUT TIDAK DAPAT DIPROSES..');
		}
		else
		{
			$('.url').removeClass('error');
			$('.url').addClass('error');
			tambah_notif('negative','MAAF..','MASUKAN URL TARGET TERLEBIH DAHULU..');
		}		
	}

	
	
}


//HAPUS NOTIF
function hapus_notif(val){
	$('#'+val).transition('fade');
}

//TAMBAH NOTIF
function tambah_notif(tipe='info', judul='Hai!' ,konten='...'){
	
	numRow++;
	var wrapper=$('#notif');
	var notif='<div class="ui '+tipe+' message" id="notif'+numRow+'">';
		notif+= '<i class="close icon" onclick="hapus_notif(\'notif'+numRow+'\')" ></i>';
		notif+= '<div class="header">';
		notif+= judul;
		notif+= '</div>';
		notif+= '<p>'+konten+'</p>';
		notif+= '</div>';

	wrapper.append(notif);	
}

//TAMPILKAN LOADER
function show_loader(){
	var wrapper=$('#notif');
	var notif='<div class="ui icon message" id="loader_proses">';
		notif+= '<i class="notched circle loading icon"></i>';
		notif+= '<div class="content">';
		notif+= '<div class="header">';
		notif+= 'Sabar..';
		notif+= '</div>';
		notif+= '<p>Proses pemendekan url sedang dilakukan.</p>';
		notif+= '</div>';
		notif+= '</div>';

	wrapper.append(notif);

}

//HIDE LOADER
function hide_loader(){
	$('#loader_proses').transition('fade');
	$('#loader_proses').remove();

}

//URL VALIDATOR
function urlValidator(val) {
	var pattern = new RegExp('^(https?:\\/\\/)?'+ // protocol
		'((([a-z\\d]([a-z\\d-]*[a-z\\d])*)\\.)+[a-z]{2,}|'+ // domain name
		'((\\d{1,3}\\.){3}\\d{1,3}))'+ // OR ip (v4) address
		'(\\:\\d+)?(\\/[-a-z\\d%_.~+]*)*'+ // port and path
		'(\\?[;&a-z\\d%_.~+=-]*)?'+ // query string
		'(\\#[-a-z\\d_]*)?$','i'); // fragment locator
	return !!pattern.test(val);
}

//SIMPLE UID GENERATOR
function simpleUIDGenerator() {
    var firstPart = (Math.random() * 46656) | 0;
    var secondPart = (Math.random() * 46656) | 0;
    firstPart = ("000" + firstPart.toString(36)).slice(-3);
    secondPart = ("000" + secondPart.toString(36)).slice(-3);
    return firstPart + secondPart;
}


$(document).ready(function() {
	get_list_url();
	 
});

//LIST DATA URL
function get_list_url(){
	
	$.ajax({
		url : "<?php echo $BASE.'/'; ?>api/get-url/<?php echo $token; ?>",
		type: "GET",
		success: function(msg)
		{
			var wrapper = $("#list_url"); 
			var psn=JSON.parse(msg);
			var konten=psn.content;
			$("#list_url").html('');
			if(konten.length>0){
				for (var i = konten.length - 1; i >= 0; i--) {
					var warna = "green";
					var status = "disable";
					if(konten[i].status==0)
					{
						warna = "red";
						status = "enable";
					}
					var list_url = '<div class="item"><div class="right floated content">';
						list_url += '<div class="ui red button" id="delete_'+konten[i].short_url+'" onclick="delete_url(\''+konten[i].short_url+'\')">Delete</div>';
						list_url += '</div>';
						list_url += '<i class="large linkify middle aligned icon"></i>';
						list_url += '<div class="content">';
						list_url += '<a class="header" href="<?php echo $BASE."/"; ?>'+konten[i].short_url+'" target="_blank" rel="external nofollow">http://'+host+'<?php echo $BASE."/"; ?>'+konten[i].short_url+'&nbsp;&nbsp;<i class="ui '+warna+' empty circular label"></i></a>';
						list_url += '<div class="description">'+konten[i].origin_url+'</div>';
						list_url += '</div>';
						list_url += '</div>';

					$(wrapper).append(list_url);
				};

			}


		}
	});	
}

function delete_url(short_url)
{
	$.ajax({
		url : "<?php echo $BASE.'/'; ?>api/delete-url/"+short_url+"/<?php echo $token; ?>",
		type: "DELETE",
		success: function(msg)
		{
			get_list_url();
		}
	});		
}

</script>
