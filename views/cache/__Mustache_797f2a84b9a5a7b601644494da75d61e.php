<?php

class __Mustache_797f2a84b9a5a7b601644494da75d61e extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';
        $blocksContext = array();

        $buffer .= $indent . '<div class="laps-timeline">
';
        // 'events' section
        $value = $context->find('events');
        $buffer .= $this->section940385acb9865793d8943db356869c18($context, $indent, $value);
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        // 'savequeries' section
        $value = $context->find('savequeries');
        $buffer .= $this->sectionB93c1beb6bc5c41326a972381b0dd94a($context, $indent, $value);
        $buffer .= $indent . '
';
        // 'savehttp' section
        $value = $context->find('savehttp');
        $buffer .= $this->section238f05e38d8d133266503e545e18de7e($context, $indent, $value);

        return $buffer;
    }

    private function section940385acb9865793d8943db356869c18(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        $blocksContext = array();
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="event event-{{category}}" data-toggle="tooltip" title="{{name}} - {{duration}} ms - {{memory}} MB" style="width:{{width}}%; left:{{offset}}%;">
            <span class="sr-only">{{name}} - {{duration}} ms - {{memory}} MB</span>
        </div>
	';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '		<div class="event event-';
                $value = $this->resolveValue($context->find('category'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" data-toggle="tooltip" title="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' - ';
                $value = $this->resolveValue($context->find('duration'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' ms - ';
                $value = $this->resolveValue($context->find('memory'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' MB" style="width:';
                $value = $this->resolveValue($context->find('width'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%; left:';
                $value = $this->resolveValue($context->find('offset'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%;">
';
                $buffer .= $indent . '            <span class="sr-only">';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' - ';
                $value = $this->resolveValue($context->find('duration'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' ms - ';
                $value = $this->resolveValue($context->find('memory'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' MB</span>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section372053fedd826e7e297c29f607930cbc(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        $blocksContext = array();
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="event event-{{category}}" title="{{sql}}" style="width:{{width}}%; left:{{offset}}%;">
            <span class="sr-only">{{sql}}</span>
        </div>
	';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '		<div class="event event-';
                $value = $this->resolveValue($context->find('category'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" title="';
                $value = $this->resolveValue($context->find('sql'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" style="width:';
                $value = $this->resolveValue($context->find('width'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%; left:';
                $value = $this->resolveValue($context->find('offset'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%;">
';
                $buffer .= $indent . '            <span class="sr-only">';
                $value = $this->resolveValue($context->find('sql'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '</span>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionB93c1beb6bc5c41326a972381b0dd94a(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        $blocksContext = array();
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="laps-timeline">
	{{#queries}}
		<div class="event event-{{category}}" title="{{sql}}" style="width:{{width}}%; left:{{offset}}%;">
            <span class="sr-only">{{sql}}</span>
        </div>
	{{/queries}}
</div>
';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '<div class="laps-timeline">
';
                // 'queries' section
                $value = $context->find('queries');
                $buffer .= $this->section372053fedd826e7e297c29f607930cbc($context, $indent, $value);
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section26362c0132f6d74333e6154005595603(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        $blocksContext = array();
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="event event-{{category}}" title="{{name}} - {{duration}} ms" style="width:{{width}}%; left:{{offset}}%;">
            <span class="sr-only">{{name}} - {{duration}} ms</span>
        </div>
	';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '		<div class="event event-';
                $value = $this->resolveValue($context->find('category'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" title="';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' - ';
                $value = $this->resolveValue($context->find('duration'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' ms" style="width:';
                $value = $this->resolveValue($context->find('width'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%; left:';
                $value = $this->resolveValue($context->find('offset'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%;">
';
                $buffer .= $indent . '            <span class="sr-only">';
                $value = $this->resolveValue($context->find('name'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' - ';
                $value = $this->resolveValue($context->find('duration'), $context);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' ms</span>
';
                $buffer .= $indent . '        </div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section238f05e38d8d133266503e545e18de7e(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        $blocksContext = array();
    
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="laps-timeline">
	{{#http}}
		<div class="event event-{{category}}" title="{{name}} - {{duration}} ms" style="width:{{width}}%; left:{{offset}}%;">
            <span class="sr-only">{{name}} - {{duration}} ms</span>
        </div>
	{{/http}}
</div>
';
            $result = call_user_func($value, $source, $this->lambdaHelper);
            if (strpos($result, '{{') === false) {
                $buffer .= $result;
            } else {
                $buffer .= $this->mustache
                    ->loadLambda((string) $result)
                    ->renderInternal($context);
            }
        } elseif (!empty($value)) {
            $values = $this->isIterable($value) ? $value : array($value);
            foreach ($values as $value) {
                $context->push($value);
                
                $buffer .= $indent . '<div class="laps-timeline">
';
                // 'http' section
                $value = $context->find('http');
                $buffer .= $this->section26362c0132f6d74333e6154005595603($context, $indent, $value);
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

}
