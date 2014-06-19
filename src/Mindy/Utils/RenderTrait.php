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
use Yii;

trait RenderTrait
{
    public function renderString($template, array $data = [])
    {
        return Mindy::app()->viewRenderer->render($template, $data);
    }

    public function renderTemplate($view, array $data = [])
    {
        if ($this->beforeRender($view)) {
            $template = $this->getViewFile($view);
            if ($template === null) {
                throw new Exception("Template not found: $view. Search paths:\n" . implode("\n", Mindy::app()->finder->getPaths()));
            }

            $output = Mindy::app()->viewRenderer->render($template, $data);
            $output = Mindy::app()->middleware->processView($output);
            $this->afterRender($view, $output);

            return $output;
        }

        return null;
    }

    public static function renderStatic($view, array $data = [])
    {
        $template = Mindy::app()->finder->find($view);
        if ($template === null) {
            throw new Exception("Template not found: $view. Search paths:\n" . implode("\n", Mindy::app()->finder->getPaths()));
        }

        $output = Mindy::app()->viewRenderer->render($template, $data);
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
     * Returns the view script file according to the specified view name.
     * This method must be implemented by child classes.
     * @param string $viewName view name
     * @return string the file path for the named view. False if the view cannot be found.
     */
    public function getViewFile($viewName)
    {
        return Mindy::app()->finder->find($viewName);
    }
}
