<?php

class __Mustache_37c708633787b0bcaf1bd7b4adc8b13a extends Mustache_Template
{
    private $lambdaHelper;

    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $this->lambdaHelper = new Mustache_LambdaHelper($this->mustache, $context);
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<div class="laps-timeline">
';
        // 'events' section
        $value = $context->find('events');
        $buffer .= $this->section83708baddd3627fe4d26675ddf7eaeb5($context, $indent, $value);
        $buffer .= $indent . '</div>
';
        $buffer .= $indent . '
';
        // 'savequeries' section
        $value = $context->find('savequeries');
        $buffer .= $this->section9756eb1d93d100052bdb80ee49b09ad6($context, $indent, $value);
        $buffer .= $indent . '
';
        // 'savehttp' section
        $value = $context->find('savehttp');
        $buffer .= $this->section3669ef98d4f6390c8df9645c96a328ee($context, $indent, $value);

        return $buffer;
    }

    private function section83708baddd3627fe4d26675ddf7eaeb5(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="event event-{{category}}" data-toggle="tooltip" title="{{name}} - {{duration}} ms - {{memory}} MB" style="width:{{width}}%; left:{{offset}}%;"></div>
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
                $value = $this->resolveValue($context->find('category'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" data-toggle="tooltip" title="';
                $value = $this->resolveValue($context->find('name'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' - ';
                $value = $this->resolveValue($context->find('duration'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' ms - ';
                $value = $this->resolveValue($context->find('memory'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' MB" style="width:';
                $value = $this->resolveValue($context->find('width'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%; left:';
                $value = $this->resolveValue($context->find('offset'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%;"></div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section8dd7d0439d6fbb51dfb44aaa94780d5c(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="event event-{{category}}" title="{{sql}}" style="width:{{width}}%; left:{{offset}}%;"></div>
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
                $value = $this->resolveValue($context->find('category'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" title="';
                $value = $this->resolveValue($context->find('sql'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" style="width:';
                $value = $this->resolveValue($context->find('width'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%; left:';
                $value = $this->resolveValue($context->find('offset'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%;"></div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section9756eb1d93d100052bdb80ee49b09ad6(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="laps-timeline">
	{{#queries}}
		<div class="event event-{{category}}" title="{{sql}}" style="width:{{width}}%; left:{{offset}}%;"></div>
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
                $buffer .= $this->section8dd7d0439d6fbb51dfb44aaa94780d5c($context, $indent, $value);
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function sectionFb9cb3f90496dc38ba05f46e0ac2ad5d(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
		<div class="event event-{{category}}" title="{{name}} - {{duration}} ms" style="width:{{width}}%; left:{{offset}}%;"></div>
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
                $value = $this->resolveValue($context->find('category'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '" title="';
                $value = $this->resolveValue($context->find('name'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' - ';
                $value = $this->resolveValue($context->find('duration'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= ' ms" style="width:';
                $value = $this->resolveValue($context->find('width'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%; left:';
                $value = $this->resolveValue($context->find('offset'), $context, $indent);
                $buffer .= htmlspecialchars($value, 2, 'UTF-8');
                $buffer .= '%;"></div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }

    private function section3669ef98d4f6390c8df9645c96a328ee(Mustache_Context $context, $indent, $value)
    {
        $buffer = '';
        if (!is_string($value) && is_callable($value)) {
            $source = '
<div class="laps-timeline">
	{{#http}}
		<div class="event event-{{category}}" title="{{name}} - {{duration}} ms" style="width:{{width}}%; left:{{offset}}%;"></div>
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
                $buffer .= $this->sectionFb9cb3f90496dc38ba05f46e0ac2ad5d($context, $indent, $value);
                $buffer .= $indent . '</div>
';
                $context->pop();
            }
        }
    
        return $buffer;
    }
}
