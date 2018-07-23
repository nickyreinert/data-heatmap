<?php


 final class Heatmap
{
	private $xAxis = [];
	private $yAxis = [];
	private $maxValue = 0;
	private $pluginName = 'data-heatmap';
	private $version = '1.0.0';

	private $error = NULL;


    public function __construct() {

		add_filter( 'get_data_heatmap_instance', [ $this, 'get_instance' ] );

	}

    public function get_instance() {

		return $this; // return the object

    }

	private function getParams($userParams) {

		$this->params = shortcode_atts( array(
			'source' => 'custom-field', // TODO: add as source: SQL-query, file from media library
			'id' => 'data-heatmap',
			'source-id' => 'data-heatmap',
			'basecolor' => '#ff0000',
			'fontSize' => 8,
			'hide-values' => 'no',
			'hide-xaxis' => 'no',
			'hide-yaxis' => 'no',
			'transpose' => 'no',
			'sort-yaxis' => 'no'
		), $userParams );

	}

	private function getDataFromSource() {

		$data = NULL;

		switch ($this->params['source']) {

			default:
			case 'custom-field':

				$data = explode(PHP_EOL, get_post_meta(get_the_ID(), $this->params['source-id'], TRUE));

				if (sizeof($data) <= 1 AND $data[0] == '') {

					$this->error = '<p class="data-heatmap-warning">Could not read data from custom-field `'.$this->params['source-id'].'`!</p>';

				}

				break;
		}


		return $data;
	}

	private function prepareRawData($data) {

		$header = [];
		$preparedData = [];
		
		$this->maxValue = 0;
		
		foreach ($data as $index => $row) {

			if ($index == 0) {

				$header = array_flip(explode(',', trim($row)));

			} else {

				$fields = explode(',', trim($row));

				if ($this->params['transpose'] == 'no') {

					$xIndex = trim($fields[(string) $header['x']]);
					$yIndex = trim($fields[(string) $header['y']]);

				} else {

					$xIndex = trim($fields[(string) $header['y']]);
					$yIndex = trim($fields[(string) $header['x']]);

				}
				$value = trim($fields[(string) $header['v']]);

				if (!in_array($xIndex, $this->xAxis)) {

					$this->xAxis[] = $xIndex;

				}

				if (!array_key_exists($yIndex, $this->yAxis)) {

						$this->yAxis[$yIndex] = $value;

				} else {

					$this->yAxis[$yIndex] += $value;

				}

				if ($value > $this->maxValue) {$this->maxValue = $value;}

				$preparedData[$yIndex][$xIndex] = $value;

			}

		}

		if ($this->params['sort-yaxis'] == 'yes') {

			arsort($this->yAxis);

		}

		return $preparedData;
	}

	private function prepareOutput($preparedData) {

		$result = NULL;

		$result = '<table class="data-heatmap" id="'.$this->params['id'].'" style="text-align: center;font-size: '.$this->params['fontSize'].'pt;">';

		if ($this->params['hide-xaxis'] == 'no') {

			$result .= '<th>';
			foreach ($this->xAxis as $xIndex) {

				$result .= '<td class="data-heatmap-xindex">'.$xIndex.'</td>';

			}
			$result .= '</th>';

		}

		foreach ($this->yAxis as $yIndex => $yValue) {

			$result .= '<tr>';

			if ($this->params['hide-yaxis'] == 'no') {

				$result .= '<td class="data-heatmap-yindex">'.$yIndex.'</td>';

			}

			foreach ($this->xAxis as $xIndex) {


				if (array_key_exists($yIndex, $preparedData) AND array_key_exists($xIndex, $preparedData[$yIndex])) {

					$value = $preparedData[$yIndex][$xIndex];

					if ($this->params['hide-values'] == 'yes') {

						$result .= '<td class="data-heatmap-cell" value="'.$value.'" style="background-color: rgba('.$this->getColor($value).');"></td>';

					} else {

						$result .= '<td style="background-color: rgba('.$this->getColor($value).');">'.$value.'</td>';

					}

				} else {

					$result .= '<td></td>';

				}

			}

			$result .= '</tr>';

		}

		$result .= '</table>';

		return $result;

	}

	private function getColor($value) {

		list($r, $g, $b) = sscanf($this->params['basecolor'], "#%02x%02x%02x");

		$a = round($value / $this->maxValue, 2);

		return $r .','. $g .','. $b .','. $a;
	}

    public function renderDataIntoHeatmap($userParams) {

		wp_enqueue_style( $this->pluginName, plugin_dir_url( __FILE__ ) . 'css/data-heatmap.css', array(), $this->version, 'all' );

		wp_enqueue_script( $this->pluginName, plugin_dir_url( __FILE__ ) . 'js/data-heatmap.js', array(), $this->version, 'all' );

		$this->getParams($userParams);

		$rawData = $this->getDataFromSource();

		if ($this->error != NULL) {

			return $this->error;
		}

		$preparedData = $this->prepareRawData($rawData);

		unset($rawData);

		$result = $this->prepareOutput($preparedData);

		unset($preparedData);

		return $result;
    }
}
