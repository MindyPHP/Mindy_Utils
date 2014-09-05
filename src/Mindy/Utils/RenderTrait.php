<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Falaleev Maxim
 * @email max@studio107.ru
 * @version 1.0
 * @company Studio107
 * @site http://studio107.ru
 * @date 09/04/14.04.2014 16:26
 */

namespace Mindy\Utils;


use Exception;
use Mindy\Base\Mindy;

trait RenderTrait
{
    public function renderString($source, array $data = [])
    {
        return Mindy::app()->template->renderString($source, $this->mergeData($data));
    }

    protected function mergeData($data)
    {
        if(is_array($data) === false) {
            $data = [];
        }
        $app = Mindy::app();
        return array_merge($data, [
            'request' => $app->getComponent('request'),
            'user' => $app->getComponent('user')
        ]);
    }

    public function renderTemplate($view, array $data = [])
    {
        if ($this->beforeRender($view)) {
            $app = Mindy::app();
            $output = $app->getComponent('template')->render($view, $this->mergeData($data));
            if($app->hasComponent('middleware')) {
                $output = $app->middleware->processView($output);
            }
            $this->afterRender($view, $output);
            return $output;
        }

        return null;
    }

    public static function renderStatic($view, array $data = [])
    {
        $output = Mindy::app()->template->render($view, $data);
        $output = Mindy::app()->middleware->processView($output);
        return $output;
    }

    /**
     * This method is invoked at the beginning of {@link render()}.
     * You may override this method to do some preprocessing when rendering a view.
     * @param string $view the view to be rendered
     * @return boolean whether the view should be rendered.
     * @since 1.1.5
     */
    protected function beforeRender($view)
    {
        return true;
    }

    /**
     * This method is invoked after the specified is rendered by calling {@link render()}.
     * Note that this method is invoked BEFORE {@link processOutput()}.
     * You may override this method to do some postprocessing for the view rendering.
     * @param string $view the view that has been rendered
     * @param string $output the rendering result of the view. Note that this parameter is passed
     * as a reference. That means you can modify it within this method.
     * @since 1.1.5
     */
    protected function afterRender($view, &$output)
    {
    }

    /**
     * Renders a view file.
     * This method includes the view file as a PHP script
     * and captures the display result if required.
     * @param string $_viewFile_ view file
     * @param array $_data_ data to be extracted and made available to the view file
     * @param boolean $_return_ whether the rendering result should be returned as a string
     * @return string the rendering result. Null if the rendering result is not required.
     */
    public function renderInternal($_viewFile_, $_data_ = null, $_return_ = false)
    {
        $_viewFile_ = Mindy::app()->finder->find($_viewFile_);

        // we use special variable names here to avoid conflict when extracting data
        if (is_array($_data_)) {
            extract($_data_, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $_data_;
        }
        if ($_return_) {
            ob_start();
            ob_implicit_flush(false);
            require($_viewFile_);
            return ob_get_clean();
        } else {
            require($_viewFile_);
        }
    }
}
