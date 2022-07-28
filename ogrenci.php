
<?php
echo "<!DOCTYPE html>
	<html>
	<head>
		<style>
			table 
			{
			font-family: arial, sans-serif;
			border-collapse: collapse;
			width: 100%;
			}
			tr:hover {background-color: rgb(210, 210, 210);}

			td, th 
			{
			border: 3px solid #a0a0a0;
			text-align: left;
			padding: 8px;
			}
		</style>
		
	</head>
	<body>";
echo "<pre>";
print_r($_GET);
echo "</pre>";
switch(@ $_GET['is'])
{
	case 'guncelleFormu'	: guncelleFormu();  break;
	case 'ekle'				: ekle(); break;
	case 'guncelle'			: guncelle();  break;
	case 'sil'				: sil($_GET['no']);  break;
	case 'eklemeFormu'		: eklemeFormu();  break;
	case 'update'           : update($_GET);

	default: listele();
}

echo " 
</body>

</html>
";
exit;
function eklemeFormu()
{
	echo "<form method=get action=?>
	No: <input name=numara value=''> <br>
	Adi:<input name=adi value=''> <br>
	Soyadi:<input name=soyadi value=''> <br>
	Bolum: <input name=bolum value=''> <br>
	<input name=is value=ekle type=hidden> 
	<input name=submit type=submit>	</form>"
	;
}

function ekle()
{
	$dosya = fopen("ogrenci.txt", "a");
	fputs($dosya, "\n{$_GET['numara']} {$_GET['adi']} {$_GET['soyadi']} {$_GET['bolum']}");
	fclose($dosya);
	header("location: ogrenci.php");
}

function update()
{
	/*$dosya = fopen("ogrenci.txt", "a");
	fputs($dosya, "{$_GET['adi']}', soyad = '{$_GET['soyadi']}', bolum = '{$_GET['bolum']}' WHERE no={$_GET['numara']};");
	fclose($dosya);
    header("location: ogrenci.php");*/
	$dosya = fopen("ogrenci.txt", "r");
	$cikti = fopen("temp.txt", "w");
	while(! feof($dosya))
	{
		$sutun = fgets($dosya);
		$dizi = explode(" ", $sutun);
		if((int)$dizi[0] == (int)$_GET['old_number'])
		{
			fputs($cikti, "{$_GET['numara']} {$_GET['adi']} {$_GET['soyadi']} {$_GET['bolum']}\n");
		}
		else
		{
			fputs($cikti, $sutun);
		}
	    
	}
	fclose($dosya);
	fclose($cikti);
	unlink("ogrenci.txt");
	rename("temp.txt", "ogrenci.txt");
	header("location: ogrenci.php");
	return;
}


function guncelleFormu()
{
	echo "
	<form method=get action=?>
	No: <input type=number name=numara value={$_GET['no']}> <br>
	Adi:<input name=adi value={$_GET['ad']}> <br>
	Soyadi:<input name=soyadi value={$_GET['soyad']}> <br>
	Bolum: <input name=bolum value={$_GET['bolum']}> <br>
	<input name=is value=update type=hidden> 
	<input name=old_number value={$_GET['no']} type=hidden> 
	<input name=submit type=submit>	</form>
	";
}
	

function sil($no)
{
	$dosya = fopen("ogrenci.txt", "r");
	$cikti = fopen("temp.txt", "w");
	while(! feof($dosya))
	{
		$sutun = fgets($dosya);
		$dizi = explode(" ", $sutun);
		if($dizi[0] != $no)
			fputs($cikti, $sutun);
	}
	fclose($dosya);
	fclose($cikti);
	unlink("ogrenci.txt");
	rename("temp.txt", "ogrenci.txt");
	header("location: ogrenci.php");
	return;
}


function listele()
{
	
	echo "<h1 style='text-align:center;'>Öğrenci listesi</h1> 
	
	<table class='js-dynamitable     table table-bordered'> <thead><tr> <th  style='background-color: rgb(180, 180, 180)'style='width:7%'>No<span class='js-sorter-desc     glyphicon glyphicon-chevron-down pull-right'></span> <span class='js-sorter-asc     glyphicon glyphicon-chevron-up pull-right'></span></th> <th style='background-color: rgb(180, 180, 180)'>Adı</th> <th style='background-color: rgb(180, 180, 180)'>Soyadı</th> <th style='background-color: rgb(180, 180, 180)'>Bölüm</th> <th style='background-color: rgb(180, 180, 180)'><a style='color:green'href='ogrenci.php?is=eklemeFormu' style='background-color: rgb(180, 180, 180)'>Yeni</a></th> <th style='background-color: rgb(180, 180, 180)'></th></tr></thead><tbody>";
	$dosya = fopen("ogrenci.txt", "r");
	while(! feof($dosya))
	{
		$sutun = explode(" ", trim(fgets($dosya)));
		if(! empty($sutun[2]))
			print "<tr> 
				<td>{$sutun[0]}</td> 
				<td>{$sutun[1]}</td> 
				<td>{$sutun[2]}</td> 
				<td>{$sutun[3]}</td> 
				<td> <a style='color:red'href='?is=sil&no={$sutun[0]}'>Sil</a>
				<td> <a style='color:green'href='?is=guncelleFormu&no={$sutun[0]}&ad={$sutun[1]}&soyad={$sutun[2]}&bolum={$sutun[3]}'>Guncelle</a>
				</td></tr>";

	}
	print "</tbody></table>";
	fclose($dosya);
	
}

/*function listeleVT(){
	
	echo "<h1>Ogrenci listesi</h1> 
	<a href='eklemeFormu.php'>Yeni</a>
	<table class='table table-dark'> <thead><tr> <th>No</th> <th>Adi</th> <th>Soyadi</th> <th>Bölüm</th> <th>Sil</th> </tr></thead><tbody>";
	$dosya = fopen("ogrenci.txt", "r");
	while(! feof($dosya)){
		$sutun = explode(" ", trim(fgets($dosya)));
		if(! empty($sutun[2]))
			print "<tr> 
				<td>{$sutun[0]}</td> 
				<td>{$sutun[1]}</td> 
				<td>{$sutun[2]}</td> 
				<td>{$sutun[3]}</td> 
				<td> <a href='?is=sil&no={$sutun[0]}'>Sil</a>
				<td> <a href='?is=guncelleFormu&no={$sutun[0]}&ad={$sutun[1]}&soyad={$sutun[2]}&bolum={$sutun[3]}'>Degistir</a>
				</td></tr>";
	}
	print "</tbody></table>";
	fclose($dosya);
}*/

?>