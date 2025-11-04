<?php

// Example styles
// This is an example of how a "style sheet" should be structured
// to asign PHPWord styles to HTML elements, classes, and inline
// styles.
function htmltodocx_styles_example() {

  // Set of default styles -
  // to set initially whatever the element is:
  // NB - any defaults not set here will be provided by PHPWord.
  $styles['default'] =
    array (
      'size' => 11,
    );

  // Element styles:
  // The keys of the elements array are valid HTML tags;
  // The arrays associated with each of these tags is a set
  // of PHPWord style definitions.
  $styles['elements'] =
    array (
      'h1' => array (
        'bold' => TRUE,
        'size' => 20,
        ),
      'h2' => array (
        'bold' => TRUE,
        'size' => 15,
        'spaceAfter' => 150,
        ),
      'h3' => array (
        'size' => 12,
        'bold' => TRUE,
        'spaceAfter' => 100,
        ),
      'li' => array (
        ),
      'ol' => array (
        'spaceBefore' => 200,
        ),
      'ul' => array (
        'spaceAfter' => 150,
        ),
      'b' => array (
        'bold' => TRUE,
        ),
      'em' => array (
        'italic' => TRUE,
        ),
      'i' => array (
        'italic' => TRUE,
        ),
      'strong' => array (
        'bold' => TRUE,
        ),
      'b' => array (
        'bold' => TRUE,
        ),
      'sup' => array (
        'superScript' => TRUE,
        'size' => 6,
        ), // Superscript not working in PHPWord
      'u' => array (
        'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE,
        ),
      'del' => array (
        'strikethrough' => true,
        ),
      's' => array (
        'strikethrough' => true,
      ),
      'strike' => array (
        'strikethrough' => true,
      ),
      'a' => array (
        'color' => '0000FF',
        'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE,
        ),
      'table' => array (
        // Note that applying a table style in PHPWord applies the relevant style to
        // ALL the cells in the table. So, for example, the borderSize applied here
        // applies to all the cells, and not just to the outer edges of the table:
        'borderColor' => '000000',
        'borderSize' => 1,
        ),
      'th' => array (
        'borderColor' => '000000',
        'borderSize' => 1,
        ),
      'td' => array (
        'borderColor' => '000000',
        'borderSize' => 1,
        ),
      'img' => array (
        'width' => 400,
        ),
      );

  // Classes:
  // The keys of the classes array are valid CSS classes;
  // The array associated with each of these classes is a set
  // of PHPWord style definitions.
  // Classes will be applied in the order that they appear here if
  // more than one class appears on an element.
  $styles['classes'] =
    array (
      'underline' => array (
        'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE,
        ),
       'purple' => array (
        'color' => '901391',
       ),
       'green' => array (
        'color' => '00A500',
       ),
      );

  // Inline style definitions, of the form:
  // array(css attribute-value - separated by a colon and a single space => array of
  // PHPWord attribute value pairs.
  $styles['inline'] =
    array(
      'text-decoration: underline' => array (
        'underline' => \PhpOffice\PhpWord\Style\Font::UNDERLINE_SINGLE,
        'line-through' => true,
      ),
      'vertical-align: left' => array (
        'align' => 'left',
      ),
      'vertical-align: middle' => array (
        'align' => 'center',
      ),
      'vertical-align: right' => array (
        'align' => 'right',
      ),
    );

  return $styles;
}
