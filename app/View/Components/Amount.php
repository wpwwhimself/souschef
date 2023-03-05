<?php

namespace App\View\Components;

use App\Models\Ingredient;
use App\Models\IngredientTemplate;
use Illuminate\View\Component;

class Amount extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public $output;
    public $outputRaw;
    public $ingredient;
    public function __construct(
        public $id,
        public $template = false,
        public $forceAmount = null,
    )
    {
        if($template){
            $this->ingredient = IngredientTemplate::findOrFail($id);
            $this->outputRaw = ($forceAmount !== null) ? $forceAmount : $this->ingredient->minimum_amount;
            if($this->outputRaw === null){
                $this->output = "bd.";
            }else if($this->ingredient->unit != "JNO"){
                $this->output = $this->outputRaw . " " . $this->ingredient->unit;
            }else{
                $rem = $this->outputRaw - floor($this->outputRaw);
                $this->output = floor($this->outputRaw) ?: "";
                if($rem == 0)         $this->output .= "░░░";
                else if($rem <= 0.25) $this->output .= "░░█";
                else if($rem <= 0.5)  $this->output .= "░██";
                else if($rem <= 1)    $this->output .= "███";
            }
        }else{
            $this->ingredient = Ingredient::findOrFail($id);
            $this->outputRaw = ($forceAmount !== null) ? $forceAmount : $this->ingredient->amount;

            if($this->ingredient->template->unit != "JNO"){
                $this->output = $this->outputRaw . " " . $this->ingredient->template->unit;
            }else{
                $rem = $this->outputRaw - floor($this->outputRaw);
                $this->output = floor($this->outputRaw) ?: "";
                if($rem == 0)         $this->output .= "░░░";
                else if($rem <= 0.25) $this->output .= "░░█";
                else if($rem <= 0.5)  $this->output .= "░██";
                else if($rem <= 1)    $this->output .= "███";
            }
        }
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.amount');
    }
}
