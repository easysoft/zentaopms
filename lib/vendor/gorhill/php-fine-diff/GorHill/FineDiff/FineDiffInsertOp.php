<?php
namespace GorHill\FineDiff;

/**
 * FINE granularity DIFF
 *
 * Computes a set of instructions to convert the content of
 * one string into another.
 *
 * Copyright (c) 2011 Raymond Hill (http://raymondhill.net/blog/?p=441)
 *
 * Licensed under The MIT License
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @copyright Copyright 2011 (c) Raymond Hill (http://raymondhill.net/blog/?p=441)
 * @link http://www.raymondhill.net/finediff/
 * @version 0.6
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

class FineDiffInsertOp extends FineDiffOp {
    public function __construct($text) {
        $this->text = $text;
    }
    public function getFromLen() {
        return 0;
    }
    public function getToLen() {
        return strlen($this->text);
    }
    public function getText() {
        return $this->text;
    }
    public function getOpcode() {
        $to_len = strlen($this->text);
        if ( $to_len === 1 ) {
            return "i:{$this->text}";
        }
        return "i{$to_len}:{$this->text}";
    }
}