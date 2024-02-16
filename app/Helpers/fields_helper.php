<?php
if (!function_exists('getFields')) {
  function getFields($data, $value = null)
  {
    if (!isset($data['name']) || !isset($data['type']) || !isset($data['label'])) {
      return 'The name, type, and label fields are required';
    }

    if ($data['type'] == 'select' && !isset($data['options'])) {
      return 'The options field is required for select type';
    }

    $html  = '<div class="form-group row">';
    $html .= '  <label for="' . $data['name'] . '" class="col-sm-2 col-form-label">' . $data['label'];

    if (isset($data['required']) && $data['required'] == true) {
      $html .= ' <span class="text-danger">*</span>';
    }

      // readonly add small text below label
    if (isset($data['readonly']) && $data['readonly'] == true) {
      $html .= '  <br /><small class="text-muted"> (tidak bisa diubah)</small>';
    }

    $html .= '  </label>';



    $html .= '  <div class="col-sm-10">';
      // $html .= '    <input class="form-control" ';

    $html .= setField($data, $value);

      // $html .= '    >';
    $html .= '  </div>';
    $html .= '</div>';

    return $html;
  }
}

if (!function_exists('setField')) {
  function setField($data, $value = null)
  {
    if ($data['type'] == 'select') {
      $html = '<select class="custom-select" ';
    } else if ($data['type'] == 'textarea') {
      $html = '<textarea class="form-control" ';
    } else {
      $html = '<input class="form-control" ';
    }

    $html .= setFieldId($data);

    if (!in_array($data['type'], ['select', 'textarea'])) {
      $html .= setFieldType($data);
    }

    $html .= setFieldName($data);
    $html .= setFieldPlaceholder($data);

    if (!in_array($data['type'], ['select', 'textarea'])) {
      $html .= setFieldValue($data, $value);
    }

    $html .= setFieldMinMaxStep($data);
    $html .= setFieldReadonly($data);
    $html .= setFieldDisabled($data);
    $html .= setFieldRequired($data);

      // end open tag
    $html .= '>';

      // end close tag
    if ($data['type'] == 'select') {
      $html .= setFieldOptions($data, $value);
      $html .= '</select>';
    } else if ($data['type'] == 'textarea') {
      $html .= setFieldValue($data, $value);
      $html .= '</textarea>';
    }

    return $html;
  }
}

if (!function_exists('setFieldType')) {
  function setFieldType($data)
  {
    if (isset($data['type'])) {
      if (!in_array($data['type'], ['select', 'textarea'])) {
        return "type='{$data['type']}' ";
      }
    }
  }
}

if (!function_exists('setFieldName')) {
  function setFieldName($data)
  {
    if (isset($data['name'])) {
      return "name='{$data['name']}' ";
    }
  }
}

if (!function_exists('setFieldId')) {
  function setFieldId($data)
  {
    if (isset($data['name'])) {
      return "id='{$data['name']}' ";
    }
  }
}

if (!function_exists('setFieldValue')) {
  function setFieldValue($data, $value)
  {
    if (isset($data['name']) && isset($value[$data['name']])) {
      return "value='{$value[$data['name']]}' ";
    }
  }
}

if (!function_exists('setFieldOptions')) {
  function setFieldOptions($data)
  {
    $selected = isset($data['selected']) ? $data['selected'] : '';
    if (isset($data['type']) && $data['type'] == 'select' && isset($data['options'])) {
      $html = '';
      $html .= "<option>--- pilih " . strtolower($data['label']) . " ---</option>";
      foreach ($data['options'] as $option) {
        $html .= "<option value='{$option->value}' ";
        if ($selected == $option->value) {
          $html .= "selected ";
        } else if (isset($data['name']) && isset($value[$data['name']]) && $value[$data['name']] == $option->value) {
          $html .= "selected ";
        }
        $html .= ">{$option->name}</option>";
      }
      return $html;
    }
  }
}

  // min max step
if (!function_exists('setFieldMinMaxStep')) {
  function setFieldMinMaxStep($data)
  {
    if (isset($data['type']) && in_array($data['type'], ['number', 'range'])) {
      $html = '';
      if (isset($data['min'])) {
        $html .= "min='{$data['min']}' ";
      }
      if (isset($data['max'])) {
        $html .= "max='{$data['max']}' ";
      }
      if (isset($data['step'])) {
        $html .= "step='{$data['step']}' ";
      }
      
      return $html;
    }
  }
}

if (!function_exists('setFieldPlaceholder')) {
  function setFieldPlaceholder($data)
  {
    if (isset($data['name']) && isset($data['label'])) {
      if ($data['type'] == 'select') {
        return "placeholder='pilih " . strtolower($data['label']) . "' ";
      } else {
        return "placeholder='input " . strtolower($data['label']) . "' ";
      }
    }
  }
}

if (!function_exists('setFieldRequired')) {
  function setFieldRequired($data)
  {
    if (isset($data['required']) && $data['required'] == true) {
      return "required ";
    }
  }
}

if (!function_exists('setFieldReadonly')) {
  function setFieldReadonly($data)
  {
    if (isset($data['readonly']) && $data['readonly'] == true) {
      return "readonly ";
    }
  }
}

if (!function_exists('setFieldDisabled')) {
  function setFieldDisabled($data)
  {
    if (isset($data['disabled']) && $data['disabled'] == true) {
      return "disabled ";
    }
  }
}

// about creator for this helper

/**
 * Fields Helper
 * Membantu, mempermudah dan mempercepat pembuatan input form, dengan basis style bootstrap 4 
 * 
 * dibuat oleh  :
 *  - name      : Muhamad Faisal Halim
 *  - email     : ffaisalhalim@gmail.com
 *  - github    : halimkun | https://github.com/halimkun
 *  - linkedin  : https://www.linkedin.com/in/faisal-halim
 *  - website   : https://halimkun.com/
 * */ 