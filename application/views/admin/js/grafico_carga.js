<script>
/**********************************************
 * 
 * Grafico avance de carga de informacion
 *
 * ****************************************/

	var ctx = document.getElementById("carga_info").getContext("2d");
    indicador_avance = '<?= $tot_dias_info / $tot_dias_habiles ?>';

	var chart = new Chart(ctx, {
		type: 'gauge',
		data: {
            labels: ['1 / 3', '2 / 3', '3 / 3'],
			datasets: [{
                data: [0.3, 0.6, 1],
				value: indicador_avance,
				backgroundColor: ['red', 'yellow', 'green'],
				borderWidth: 2
			}]
		},
          options: {
			  responsive: true,
			  layout: {
				  padding: {
					  bottom: 30
				  }
			  },
			  needle: {
				  // Needle circle radius as the percentage of the chart area width
				  radiusPercentage: 2,
				  // Needle width as the percentage of the chart area width
				  widthPercentage: 3.2,
				  // Needle length as the percentage of the interval between inner radius (0%) and outer radius (100%) of the arc
				  lengthPercentage: 80,
				  // The color of the needle
				  color: 'rgba(0, 0, 0, 1)'
			  },
			  valueLabel: {
				  display: false
			  },
			  plugins: {
				  datalabels: {
					  display: true,
					  formatter:  function (value, context) {
						  return context.chart.data.labels[context.dataIndex];
					  },
					  //color: function (context) {
					  //  return context.dataset.backgroundColor;
					  //},
					  color: 'rgba(0, 0, 0, 1.0)',
					  //color: 'rgba(255, 255, 255, 1.0)',
					  backgroundColor: null,
					  font: {
						  size: 14,
						  weight: 'normal'
					  }
				  }
			  }
		  }
	});

</script>
