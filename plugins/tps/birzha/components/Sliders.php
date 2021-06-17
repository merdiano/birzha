<?php namespace TPS\Birzha\Components;

use Cms\Classes\ComponentBase;
use TPS\Birzha\Models\Slider;

class Sliders extends ComponentBase
{
    public function componentDetails() {
        return [
            'name' => 'Slider list',
            'description' => 'List of sliders'
        ];
    }

    public function defineProperties() {
        return [
            'sliderCode' => [
                'title' => 'Choose slider',
                'description' => 'What slider to place?',
                'type' => 'dropdown',
                'default' => ''
            ],
            'sliderType' => [
                'title' => 'Choose type',
                'description' => 'Slider type?',
                'type' => 'dropdown',
                'default' => ''
            ]
        ];
    }

    public function getSliderCodeOptions() {
        return Slider::pluck('code','code')->toArray();
    }

    public function getSliderTypeOptions() {
        return [
            'text_slider' => 'Text slider',
            'img_slider' => 'Image slider'
        ];
    }

    public function onRun() {
        $this->slider = $this->loadSliders();
    }

    protected function loadSliders() {
        if($this->property('sliderCode') == '') {
            return null;
        } else {
            return Slider::where('code', $this->property('sliderCode'))->first();
        }
    }

    public $slider;
}