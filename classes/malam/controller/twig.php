<?php

defined('SYSPATH') or die('No direct script access.');

/**
 * @author  arie
 */

abstract class Malam_Controller_Twig extends Controller
{
    /**
     * @var boolean  Auto-render template after controller method returns
     */
    public $auto_render = TRUE;

    /**
     * @var Malam_View
     */
    public $template;
    
    /**
     * @var Malam_Temporary
     */
    public $temporary;
    
    public function __construct(Request $request, Response $response)
    {
        parent::__construct($request, $response);
        
        $this->temporary = new Malam_Temporary;
    }
    
    public function before()
    {}
    
    public function after()
    {
        if ($this->auto_render)
        {
            if ($this->template === NULL)
            {
                $this->template = Inflector::singular($this->request->controller()).'/'.$this->request->action();

                if ($this->request->directory())
                {
                    $this->template = $this->request->directory().'/'.$this->template;
                }
            }

            $this->template = Malam_View::factory($this->template);
            $this->template->set($this->temporary->as_array());
            $this->response->body($this->template->render());
        }
    }
}