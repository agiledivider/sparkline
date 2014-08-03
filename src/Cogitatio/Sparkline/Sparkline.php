<?php
namespace Cogitatio\Sparkline;
use Cogitatio\Sparkline\Renderer\SvgRenderer;
class Sparkline
{

    private $_width;
    private $_height;
    private $_rawDataSets = array();
    private $_renderer;
    private $_dataBoundries;
    private $_options = array();

    public function __construct($width, $height, $options = null)
    {
        $this->_width = (int) $width;
        $this->_height = (int) $height;

        if ($options) {
            $this->_options = array_merge_recursive($this->_options, $options);
        }
        $this->_renderer = new SvgRenderer($this->_width, $this->_height);
    }

    public function addDataSet(array $dataSet, $id)
    {
        usort($dataSet, array($this, '_sortData'));
        $this->_rawDataSets[$id] = $dataSet;
        $this->_dataBoundries = $this->_getDataBoundries();
    }

    public function addSpark($type, $dataSets, $options = null)
    {
        switch ($type) {
            case 'difference':
                $this->_addDifference($dataSets[0], $dataSets[1]);
                break;
            case 'line':
                $this->_addLine($dataSets[0], $options);
                break;
            case 'percentShift':
                $this->_addPercentShift($dataSets[0], $options);
                break;
            default:
                throw new \InvalidArgumentException('Opps, ' . $type . ' is not a valid Sparkline type.');
        }
    }

    private function _addLine($dataSetName, $options)
    {
        $defaultOptions = array(
            'class' => 'line'
        );
        if ($options) {
            $defaultOptions = array_merge($defaultOptions, $options);
        }

        $line = $this->_normalizeData($this->_rawDataSets[$dataSetName]);
        $this->_renderer->addLine(
            $line,
            $defaultOptions
        );
    }

    private function _addDifference($dataSetNameTop, $dataSetNameBottom)
    {
        $top = $this->_normalizeData($this->_rawDataSets[$dataSetNameTop]);
        $bottom = $this->_normalizeData($this->_rawDataSets[$dataSetNameBottom]);

        $this->_renderer->addDifference($top, $bottom);
    }

    private function _addPercentShift($dataSetName)
    {
        $this->_options['yRange'] = array(0, 100);
        $this->_dataBoundries = $this->_getDataBoundries();
        $this->_renderer->addPercentShift(
            $this->_normalizeData($this->_rawDataSets[$dataSetName])
        );
    }

    private function _normalizeData($dataSet)
    {
        $xRatio = ($this->_dataBoundries['x']['max'] - $this->_dataBoundries['x']['min']) / $this->_width;
        $yRatio = ($this->_dataBoundries['y']['max'] - $this->_dataBoundries['y']['min']) / $this->_height;

        $normalizedDataSet = array();
        for ($i = 0; $i < count($dataSet); $i++) {
            array_push($normalizedDataSet, array(
                $xRatio == 0 ? 0 : ($dataSet[$i][0] - $this->_dataBoundries['x']['min']) / $xRatio,
                $yRatio == 0 ? 0 : ($dataSet[$i][1] - $this->_dataBoundries['y']['min']) / $yRatio
            ));
        }

        return $normalizedDataSet;
    }

    private function _sortData($data1, $data2)
    {
        return ($data1[0] < $data2[0]) ? -1 : 1;
    }

    private function _getDataBoundries($DataSetIds = null)
    {

        $dataSetKeys = array_keys($this->_rawDataSets);

        $x_max = $this->_rawDataSets[$dataSetKeys[0]][0][0];
        $x_min = $this->_rawDataSets[$dataSetKeys[0]][0][0];
        $y_max = $this->_rawDataSets[$dataSetKeys[0]][0][1];
        $y_min = $this->_rawDataSets[$dataSetKeys[0]][0][1];

        foreach ($this->_rawDataSets as $dataSet) {
            foreach ($dataSet as $value) {
                $x_max = max($x_max, $value[0]);
                $x_min = min($x_min, $value[0]);
                $y_max = max($y_max, $value[1]);
                $y_min = min($y_min, $value[1]);
            }
        }

        return array(
            'x' => array(
                'min' => isset($this->_options['xRange']) ? $this->_options['xRange'][0] : $x_min,
                'max' => isset($this->_options['xRange']) ? $this->_options['xRange'][1] : $x_max
            ),
            'y' => array(
                'min' => isset($this->_options['yRange']) ? $this->_options['yRange'][0] : $y_min,
                'max' => isset($this->_options['yRange']) ? $this->_options['yRange'][1] : $y_max
            )
        );
    }

    public function render()
    {
        return $this->_renderer->render();
    }
}
