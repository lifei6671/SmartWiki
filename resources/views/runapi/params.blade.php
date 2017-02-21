<tr>
     <td style="width: 100px;padding-right: 20px;"><label class="{{ isset($enabled) ? '' : 'hide' }}"><input type="checkbox"{{isset($enabled) ? ' checked' : ''}}></label></td>
     <td style="width: 50%;"><input type="text" class="input-text" placeholder="key" name="key" value="{{$key or ''}}"></td>
     <td style="width: 50%;padding-left: 15px;"><input type="text" class="input-text" name="value" placeholder="value" value="{{$value or ''}}"></td>
     <td style="width: 100px;padding-left: 20px;">
         <a href="###" class="parameter-close{{isset($key) ? '' : ' hide'}}">
             <i class="fa fa-close"></i>
         </a>
     </td>
</tr>
