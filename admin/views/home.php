<?php 
	$i = 0;
	$j = 0;
	$majorInCourse = $data['majorInCourse'];
	foreach ($data['status'] as $status => $item) {
		foreach ($item as $key => $subItem) {
			$a[$j][$key] = 1;
			$arr[$status][$i]['y'] = intval($subItem);
			$arr[$status][$i]['label'] = $key;
			$i++;
		}			
		foreach ($majorInCourse as $key => $tem) {
			if(!isset($a[$j][$key])) {		
				$arr[$status][$i]['y'] = 0;					
				$arr[$status][$i]['label'] = $key;	
				$i++;
			}			
		}
		$j++;	
		$i = 0;			
	}
	// dump($arr);
?>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_header.php");?>
<div class="container">
	<div class="row chart-home">
		<div class="col-md-5">
			<div id="chartContainer1" style="height: 300px; width: 100%;"></div>		
		</div>
		<div class="col-md-7">
			<div id="chartContainer2" style="height: 400px; width: 100%;"></div>		
		</div>
	</div>	
</div>


<script type="text/javascript">
	window.onload = function () {
		var chart1 = new CanvasJS.Chart("chartContainer1", {
			title: {
				text: "Participation rates for course at all majors",
				fontSize: 20,
			},
			legend:{
				verticalAlign: "center",
				horizontalAlign: "left",
				fontSize: 13,
				fontFamily: "Helvetica"        
			},
			animationEnabled: true,
			theme: "theme2",
			data: [
			{
				type: "doughnut",
				indexLabelFontFamily: "Garamond",
				indexLabelFontSize: 15,
				startAngle: 0,
				indexLabelFontColor: "dimgrey",
				indexLabelLineColor: "darkgrey",
				showInLegend: true,
				toolTipContent: "{y} %",

				dataPoints: <?php echo json_encode($data['dataPoints'], JSON_NUMERIC_CHECK); ?>
			}
			]
		});
		var chart2 = new CanvasJS.Chart("chartContainer2",
		{
			title:{
				text: "Statistics Outcome In The Trainee Learning", 
				fontSize: 20,            
			},
			axisY:{
				title: "percent"
			},
	                animationEnabled: true,
			toolTip:{
				shared: true,
				content: "{name}: {y} - <strong>#percent%</strong>",
			},
			data:[


			<?php foreach ($arr as $status => $val): ?>
			{        
				type: "stackedBar100",
				showInLegend: true, 
				name: "<?php echo ucfirst($status) ?>",
				<?php 
					$size = count($val);
					for($i = 0; $i < $size; $i++){
						for($j = 1; $j < $size; $j++){
							if(strcmp($val[$i]['label'], $val[$j]['label']) > 0){
								$temp = $val[$i];
								$val[$i] = $val[$j];
								$val[$j] = $temp;
							}
						}
					}
				?>
				dataPoints: <?php echo json_encode($val, JSON_NUMERIC_CHECK); ?>
			},			
			<?php endforeach ?>			 
			]
		});
		
		chart1.render();
		chart2.render();
	}
</script>
<?php include($_SERVER['DOCUMENT_ROOT']."/admin/views/inc/_footer.php");?>