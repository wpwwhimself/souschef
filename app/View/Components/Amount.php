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
    public $ingredient;
    public function __construct(
        public $id,
        public $template = false,
        public $forceAmount = null,
    )
    {
        if($template){
            $this->ingredient = IngredientTemplate::findOrFail($id);
            $ingr_amount = $forceAmount ?? $this->ingredient->minimum_amount;

            if($ingr_amount === null){
                $this->output = "bd.";
            }else if($this->ingredient->unit != "JNO"){
                $this->output = $ingr_amount . " " . $this->ingredient->unit;
            }else{
                $rem = $ingr_amount - floor($ingr_amount);
                $this->output = floor($ingr_amount) ?: "";
                if($rem == 0)         $this->output .= "░░░";
                else if($rem <= 0.25) $this->output .= "░░█";
                else if($rem <= 0.5)  $this->output .= "░██";
                else if($rem <= 1)    $this->output .= "███";
            }
        }else{
            $this->ingredient = Ingredient::findOrFail($id);
            $ingr_amount = $forceAmount ?? $this->ingredient->amount;

            if($this->ingredient->template->unit != "JNO"){
                $this->output = $ingr_amount . " " . $this->ingredient->template->unit;
            }else{
                $rem = $ingr_amount - floor($ingr_amount);
                $this->output = floor($ingr_amount) ?: "";
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
