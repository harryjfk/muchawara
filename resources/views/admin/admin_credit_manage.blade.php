@extends('admin.layouts.admin')
@section('content')
@parent
<div class="content-wrapper">
   <!-- Content Header (Page header) -->
   <section class="content-header content-header-custom">
      <h1 class="content-header-head">{{trans_choice('admin.credit_management',1)}}</h1>
   </section>
   <!-- Main content -->
   <section class="content">


      <div class="col-md-12 section-first-col">
         <div class="row">


<div class="col-md-12 section-first">

              <h4 class="user-statistics">{{trans_choice('admin.credit_stat',1)}}</h4>
               <div class="row">
                <div class="col-md-4">
                  <p class="total-users">{{trans_choice('admin.overall_cred',1)}}</p>
                  <p class="total-users-count">{{{$overallcredits}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="this-month">{{trans_choice('admin.cred_purchased_month',1)}}</p>
                  <p class="total-users-count">{{{$creditspurchagedthismonth}}}</p>
                </div>
                 <div class="col-md-4">
                  <p class="today">{{trans_choice('admin.cred_purchased_today',1)}}</p>
                  <p class="total-users-count">{{{$creditspurchasedtoday}}}</p>
                </div>

                <div class="col-md-4">
                  <p class="today">{{trans_choice('admin.overall_cred_used',1)}}</p>
                  <p class="total-users-count">{{{$creditsusedoverall}}}</p>
                </div>
                <div class="col-md-4">
                  <p class="today">{{trans_choice('admin.overall_cred_used_month',1)}}</p>
                  <p class="total-users-count">{{{$creditsusedthismonth}}}</p>
                </div>
                <div class="col-md-4">
                  <p class="today">{{trans_choice('admin.overall_cred_used_today',1)}}</p>
                  <p class="total-users-count">{{{$creditsusedtoday}}}</p>
                </div>
               </div>
            </div>



            <div class="col-md-12 credits-first-col" style="min-height:0px;">

               <p class="default-credit-settings" style="display:inline-flex;">{{{trans('admin.double_credits_superpowers_title')}}}
                  <label class="switch" style="margin-left: 13px;top: 3px;">
                     <input class="switch-input" type="checkbox" id ="double-credit-superpower-btn" @if($double_credits_superpower_users == 'true') checked @endif/>
                     <span class="switch-label" ></span> 
                     <span class="switch-handle"></span> 
                  </label>
               </p>
                   
            </div>


            <div class="col-md-12 credits-first-col" style="min-height:0px;">

               <p class="default-credit-settings" style="display:inline-flex;">{{{trans('admin.spotlight_only_superpowers_title')}}}
                  <label class="switch" style="margin-left: 13px;top: 3px;">
                     <input class="switch-input" type="checkbox" id ="spotlight_only_superpowers" @if($spotlight_only_superpowers == 'true') checked @endif/>
                     <span class="switch-label" ></span> 
                     <span class="switch-handle"></span> 
                  </label>
               </p>
                   
            </div>


            <div class="col-md-12 credits-first-col" style="min-height:0px;">

               <p class="default-credit-settings" style="display:inline-flex;">{{{trans('admin.peoplenearby_only_superpowers_title')}}}
                  <label class="switch" style="margin-left: 13px;top: 3px;">
                     <input class="switch-input" type="checkbox" id ="peoplenearby_only_superpowers" @if($peoplenearby_only_superpowers == 'true') checked @endif/>
                     <span class="switch-label" ></span> 
                     <span class="switch-handle"></span> 
                  </label>
               </p>
                   
            </div>


            <div class="col-md-12 credits-first-col" style="min-height:0px;">

               <p class="default-credit-settings" style="display:inline-flex;">{{{trans('admin.credits_module_available_title')}}}
                  <label class="switch" style="margin-left: 13px;top: 3px;">
                     <input class="switch-input" type="checkbox" id ="credits_module_available" @if($credits_module_available == 'true') checked @endif/>
                     <span class="switch-label" ></span> 
                     <span class="switch-handle"></span> 
                  </label>
               </p>
                   
            </div>



            <div class="col-md-12 credits-first-col">

               <p class="default-credit-settings">{{{trans('admin.default_currency_settings')}}}</p>
               <div class="row">
                  <form action = "{{{ url('/admin/creditManage/set/currency') }}}" method = "POST" id = "set-currency-form">
                     {!! csrf_field() !!}
                     <!-- <div class="col-md-3"> -->
                        <p class="default-credit-text">{{{trans('admin.choose_default_currency')}}}</p>
                        
                        <select name = "currency" class="form-control input-border-custom select-custom">
                                <option value = "" >Choose Currency</option>  
                                
                                 <option value="AFN" @if($currency == 'AFN') selected @endif>Afghanistan Afghanis – AFN</option>
                                 <option value="ALL" @if($currency == 'ALL') selected @endif>Albania Leke – ALL</option>
                                 <option value="DZD" @if($currency == 'DZD') selected @endif>Algeria Dinars – DZD</option>
                                 <option value="ARS" @if($currency == 'ARS') selected @endif>Argentina Pesos – ARS</option>
                                 <option value="AUD" @if($currency == 'AUD') selected @endif>Australia Dollars – AUD</option>
                                 <option value="ATS" @if($currency == 'ATS') selected @endif>Austria Schillings – ATS</option>
                                 <option value="BSD" @if($currency == 'BSD') selected @endif>Bahamas Dollars – BSD</option>
                                 <option value="BHD" @if($currency == 'BHD') selected @endif>Bahrain Dinars – BHD</option>
                                 <option value="BDT" @if($currency == 'BDT') selected @endif>Bangladesh Taka – BDT</option>
                                 <option value="BBD" @if($currency == 'BBD') selected @endif>Barbados Dollars – BBD</option>
                                 <option value="BEF" @if($currency == 'BEF') selected @endif>Belgium Francs – BEF</option>
                                 <option value="BMD" @if($currency == 'BMD') selected @endif>Bermuda Dollars – BMD</option>
                                 <option value="BRL" @if($currency == 'BRL') selected @endif>Brazil Reais – BRL</option>
                                 <option value="BGN" @if($currency == 'BGN') selected @endif>Bulgaria Leva – BGN</option>
                                 <option value="CAD" @if($currency == 'CAD') selected @endif>Canada Dollars – CAD</option>
                                 <option value="XOF" @if($currency == 'XOF') selected @endif>CFA BCEAO Francs – XOF</option>
                                 <option value="XAF" @if($currency == 'XAF') selected @endif>CFA BEAC Francs – XAF</option>
                                 <option value="CLP" @if($currency == 'CLP') selected @endif>Chile Pesos – CLP</option>
                                 <option value="CNY" @if($currency == 'CNY') selected @endif>China Yuan Renminbi – CNY</option>
                                 <option value="CNY" @if($currency == 'CNY') selected @endif>RMB (China Yuan Renminbi) – CNY</option>
                                 <option value="COP" @if($currency == 'COP') selected @endif>Colombia Pesos – COP</option>
                                 <option value="XPF" @if($currency == 'XPF') selected @endif>CFP Francs – XPF</option>
                                 <option value="CRC" @if($currency == 'CRC') selected @endif>Costa Rica Colones – CRC</option>
                                 <option value="HRK" @if($currency == 'HRK') selected @endif>Croatia Kuna – HRK</option>
                                 <option value="CYP" @if($currency == 'CYP') selected @endif>Cyprus Pounds – CYP</option>
                                 <option value="CZK" @if($currency == 'CZK') selected @endif>Czech Republic Koruny – CZK</option>
                                 <option value="DKK" @if($currency == 'DKK') selected @endif>Denmark Kroner – DKK</option>
                                 <option value="DEM" @if($currency == 'DEM') selected @endif>Deutsche (Germany) Marks – DEM</option>
                                 <option value="DOP" @if($currency == 'DOP') selected @endif>Dominican Republic Pesos – DOP</option>
                                 <option value="NLG" @if($currency == 'NLG') selected @endif>Dutch (Netherlands) Guilders – NLG</option>
                                 <option value="XCD" @if($currency == 'XCD') selected @endif>Eastern Caribbean Dollars – XCD</option>
                                 <option value="EGP" @if($currency == 'EGP') selected @endif>Egypt Pounds – EGP</option>
                                 <option value="EEK" @if($currency == 'EEK') selected @endif>Estonia Krooni – EEK</option>
                                 <option value="EUR" @if($currency == 'EUR') selected @endif>Euro – EUR</option>
                                 <option value="FJD" @if($currency == 'FJD') selected @endif>Fiji Dollars – FJD</option>
                                 <option value="FIM" @if($currency == 'FIM') selected @endif>Finland Markkaa – FIM</option>
                                 <option value="FRF" @if($currency == 'FRF') selected @endif>France Francs – FRF</option>
                                 <option value="DEM" @if($currency == 'DEM') selected @endif>Germany Deutsche Marks – DEM</option>
                                 <option value="XAU" @if($currency == 'XAU') selected @endif>Gold Ounces – XAU</option>
                                 <option value="GRD" @if($currency == 'GRD') selected @endif>Greece Drachmae – GRD</option>
                                 <option value="GTQ" @if($currency == 'GTQ') selected @endif>Guatemalan Quetzal – GTQ</option>
                                 <option value="NLG" @if($currency == 'NLG') selected @endif>Holland (Netherlands) Guilders – NLG</option>
                                 <option value="HKD" @if($currency == 'HKD') selected @endif>Hong Kong Dollars – HKD</option>
                                 <option value="HUF" @if($currency == 'HUF') selected @endif>Hungary Forint – HUF</option>
                                 <option value="ISK" @if($currency == 'ISK') selected @endif>Iceland Kronur – ISK</option>
                                 <option value="XDR" @if($currency == 'XDR') selected @endif>IMF Special Drawing Right – XDR</option>
                                 <option value="INR" @if($currency == 'INR') selected @endif>India Rupees – INR</option>
                                 <option value="IDR" @if($currency == 'IDR') selected @endif>Indonesia Rupiahs – IDR</option>
                                 <option value="IRR" @if($currency == 'IRR') selected @endif>Iran Rials – IRR</option>
                                 <option value="IQD" @if($currency == 'IQD') selected @endif>Iraq Dinars – IQD</option>
                                 <option value="IEP" @if($currency == 'IEP') selected @endif>Ireland Pounds – IEP</option>
                                 <option value="ILS" @if($currency == 'ILS') selected @endif>Israel New Shekels – ILS</option>
                                 <option value="ITL" @if($currency == 'ITL') selected @endif>Italy Lire – ITL</option>
                                 <option value="JMD" @if($currency == 'JMD') selected @endif>Jamaica Dollars – JMD</option>
                                 <option value="JPY" @if($currency == 'JPY') selected @endif>Japan Yen – JPY</option>
                                 <option value="JOD" @if($currency == 'JOD') selected @endif>Jordan Dinars – JOD</option>
                                 <option value="KES" @if($currency == 'KES') selected @endif>Kenya Shillings – KES</option>
                                 <option value="KRW" @if($currency == 'KRW') selected @endif>Korea (South) Won – KRW</option>
                                 <option value="KWD" @if($currency == 'KWD') selected @endif>Kuwait Dinars – KWD</option>
                                 <option value="LBP" @if($currency == 'LBP') selected @endif>Lebanon Pounds – LBP</option>
                                 <option value="LUF" @if($currency == 'LUF') selected @endif>Luxembourg Francs – LUF</option>
                                 <option value="MYR" @if($currency == 'MYR') selected @endif>Malaysia Ringgits – MYR</option>
                                 <option value="MTL" @if($currency == 'MTL') selected @endif>Malta Liri – MTL</option>
                                 <option value="MUR" @if($currency == 'MUR') selected @endif>Mauritius Rupees – MUR</option>
                                 <option value="MXN" @if($currency == 'MXN') selected @endif>Mexico Pesos – MXN</option>
                                 <option value="MAD" @if($currency == 'MAD') selected @endif>Morocco Dirhams – MAD</option>
                                 <option value="NLG" @if($currency == 'NLG') selected @endif>Netherlands Guilders – NLG</option>
                                 <option value="NZD" @if($currency == 'NZD') selected @endif>New Zealand Dollars – NZD</option>
                                 <option value="NOK" @if($currency == 'NOK') selected @endif>Norway Kroner – NOK</option>
                                 <option value="OMR" @if($currency == 'OMR') selected @endif>Oman Rials – OMR</option>
                                 <option value="PKR" @if($currency == 'PKR') selected @endif>Pakistan Rupees – PKR</option>
                                 <option value="XPD" @if($currency == 'XPD') selected @endif>Palladium Ounces – XPD</option>
                                 <option value="PEN" @if($currency == 'PEN') selected @endif>Peru Nuevos Soles – PEN</option>
                                 <option value="PHP" @if($currency == 'PHP') selected @endif>Philippines Pesos – PHP</option>
                                 <option value="XPT" @if($currency == 'XPT') selected @endif>Platinum Ounces – XPT</option>
                                 <option value="PLN" @if($currency == 'PLN') selected @endif>Poland Zlotych – PLN</option>
                                 <option value="PTE" @if($currency == 'PTE') selected @endif>Portugal Escudos – PTE</option>
                                 <option value="QAR" @if($currency == 'QAR') selected @endif>Qatar Riyals – QAR</option>
                                 <option value="RON" @if($currency == 'RON') selected @endif>Romania New Lei – RON</option>
                                 <option value="ROL" @if($currency == 'ROL') selected @endif>Romania Lei – ROL</option>
                                 <option value="RUB" @if($currency == 'RUB') selected @endif>Russia Rubles – RUB</option>
                                 <option value="SAR" @if($currency == 'SAR') selected @endif>Saudi Arabia Riyals – SAR</option>
                                 <option value="XAG" @if($currency == 'XAG') selected @endif>Silver Ounces – XAG</option>
                                 <option value="SGD" @if($currency == 'SGD') selected @endif>Singapore Dollars – SGD</option>
                                 <option value="SKK" @if($currency == 'SKK') selected @endif>Slovakia Koruny – SKK</option>
                                 <option value="SIT" @if($currency == 'SIT') selected @endif>Slovenia Tolars – SIT</option>
                                 <option value="ZAR" @if($currency == 'ZAR') selected @endif>South Africa Rand – ZAR</option>
                                 <option value="KRW" @if($currency == 'KRW') selected @endif>South Korea Won – KRW</option>
                                 <option value="ESP" @if($currency == 'ESP') selected @endif>Spain Pesetas – ESP</option>
                                 <option value="XDR" @if($currency == 'XDR') selected @endif>Special Drawing Rights (IMF) – XDR</option>
                                 <option value="LKR" @if($currency == 'LKR') selected @endif>Sri Lanka Rupees – LKR</option>
                                 <option value="SDD" @if($currency == 'SDD') selected @endif>Sudan Dinars – SDD</option>
                                 <option value="SEK" @if($currency == 'SEK') selected @endif>Sweden Kronor – SEK</option>
                                 <option value="CHF" @if($currency == 'CHF') selected @endif>Switzerland Francs – CHF</option>
                                 <option value="TWD" @if($currency == 'TWD') selected @endif>Taiwan New Dollars – TWD</option>
                                 <option value="THB" @if($currency == 'THB') selected @endif>Thailand Baht – THB</option>
                                 <option value="TTD" @if($currency == 'TTD') selected @endif>Trinidad and Tobago Dollars – TTD</option>
                                 <option value="TND" @if($currency == 'TND') selected @endif>Tunisia Dinars – TND</option>
                                 <option value="TRY" @if($currency == 'TRY') selected @endif>Turkey New Lira – TRY</option>
                                 <option value="AED" @if($currency == 'AED') selected @endif>United Arab Emirates Dirhams – AED</option>
                                 <option value="GBP" @if($currency == 'GBP') selected @endif>United Kingdom Pounds – GBP</option>
                                 <option value="USD" @if($currency == 'USD') selected @endif>United States Dollars – USD</option>
                                 <option value="VEB" @if($currency == 'VEB') selected @endif>Venezuela Bolivares – VEB</option>
                                 <option value="VND" @if($currency == 'VND') selected @endif>Vietnam Dong – VND</option>
                                 <option value="ZMK" @if($currency == 'ZMK') selected @endif>Zambia Kwacha – ZMK</option>


                       </select>
                        <button type="button" id = "set-currency-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.add_cred',1)}}</button>
                     <!-- </div> -->
                  </form>
              
               </div>
            </div>






			{{--<div class="col-md-12 credits-first-col">--}}

               {{--<p class="default-credit-settings">{{{trans('admin.super_power_settings')}}}</p>--}}
               {{--<div class="row">--}}
                  {{--<form action = "{{{ url('/admin/creditManage/defaultSuperPower') }}}" method = "POST" id = "set-default-bullets-by-command-form">--}}
                     {{--{!! csrf_field() !!}--}}
                     {{--<!-- <div class="col-md-3"> -->--}}
                        {{--<p class="default-credit-text">{{{trans('admin.default_new_user_super_power_settings')}}}</p>--}}
                        {{--<input type="number" value="@if($defaultsuperpower){{{$defaultsuperpower}}}@endif" placeholder="{{trans_choice('admin.duration',1)}}" class="form-control input-border-custom" name="defaultsuperpower">--}}
                        {{----}}
                        {{--<button type="button" id = "set-default-super-power-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.add_cred',1)}}</button>--}}
                     {{--<!-- </div> -->--}}
                  {{--</form>--}}
              {{----}}
               {{--</div>--}}
            {{--</div>--}}

             <div class="col-md-12 credits-first-col">

                 <p class="default-credit-settings">{{{trans('admin.super_power_settings')}}}</p>
                 <div class="row">
                     <form action = "{{{ url('/admin/creditManage/defaultSuperPower') }}}" method = "POST" id = "set-default-super-power-form">
                     {!! csrf_field() !!}
                     <!-- <div class="col-md-3"> -->
                         <p class="default-credit-text">{{{trans('admin.default_new_user_super_power_settings')}}}</p>
                         <input type="number" value="@if($defaultsuperpower){{{$defaultsuperpower}}}@endif" placeholder="{{trans_choice('admin.duration',1)}}" class="form-control input-border-custom" name="defaultsuperpower">

                         <button type="button" id = "set-default-super-power-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.add_cred',1)}}</button>
                         <!-- </div> -->
                     </form>

                 </div>
             </div>










         @if($credits_module_available == 'true')
            <div class="col-md-12 credits-first-col" >

               <p class="default-credit-settings">{{trans_choice('admin.cred_settings',1)}}</p>
               <div class="row">
                  <form action = "{{{ url('admin/creditManage/defaultCredAdd') }}}" method = "POST" id = "set-default-credit-form">
                     {!! csrf_field() !!}
                     <div class="col-md-3">
                        <p class="default-credit-text">{{trans_choice('admin.default_cred',1)}}</p>
                        <input type="number" value="@if($defaultcredits){{{$defaultcredits}}}@endif" class="form-control input-border-custom" name="defaultCredits">
                        <button type="button" id = "set-default-credit-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.add_cred',1)}}</button>
                     </div>
                  </form>
                  <form action = "{{{ url('admin/creditManage/credAddAll') }}}" method = "POST" id = "set-credit-all-form">
                     {!! csrf_field() !!}
                     <div class="col-md-3">
                        <p class="default-credit-text">{{trans_choice('admin.cred_all',1)}}</p>
                        <input type="number" value="" class="form-control input-border-custom" name="credit">
                        <button type="button" id = "set-credit-all-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.credall',1)}}</button>
                     </div>
                  </form>
                  <form action = "{{{ url('admin/creditManage/spotCred') }}}" method = "POST" id = "set-spotlight-credit-form">
                     {!! csrf_field() !!}    
                     <div class="col-md-2">
                        <p class="default-credit-text">{{trans_choice('admin.cred_spot',1)}}</p>
                        <input type="number" value="@if($spotcredits){{{$spotcredits}}}@endif" class="form-control input-border-custom" name="credits">
                        <button type="button" id = "set-spotlight-credit-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.add_cred',1)}}</button>
                     </div>
                  </form>
                  <form action = "{{{ url('admin/creditManage/riseupCred') }}}" method = "POST" id = "set-riseup-credit-form">
                     {!! csrf_field() !!}  
                     <div class="col-md-2">
                        <p class="default-credit-text">{{trans_choice('admin.cred_riseup',1)}}</p>
                        <input type="number" value="@if($riseupcredits){{{$riseupcredits}}}@endif" class="form-control input-border-custom" name="credits">
                        <button type="button" id = "set-riseup-credit-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.add_cred',1)}}</button>
                     </div>
                  </form>
                   <form action = "{{{ url('admin/creditManage/addBulletsCommands') }}}" method = "POST" id = "set-add-bullets-by-commands-form">
                       {!! csrf_field() !!}
                       <div class="col-md-2">
                           <p class="default-credit-text">{{trans_choice('admin.add_bullets_by_commands', 0)}}</p>
                           <input type="number" value="@if($add_bullets_by_commands){{{$add_bullets_by_commands}}}@endif" class="form-control input-border-custom" name="credits">
                           <button type="button" id = "set-add-bullets-by-commands-btn" class="btn btn-info btn-addcredits btn-custom">{{trans_choice('admin.add_cred',1)}}</button>
                       </div>
                   </form>
               </div>
            </div>
            @endif
            
            @if($credits_module_available == 'true')      
            <form action = "{{{ url('/admin/credit_package/add') }}}" method = "POST" id = "add-credit-package-form">
               {!! csrf_field() !!}
               <div class="col-md-5 add-creditpackage-col">
                  <p class="add-credit-package-text">{{trans_choice('admin.add_cred_pack',1)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.pack_name',1)}}</label>
                     <input type="text" placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.pack_name',1)}}" name = "packageName" class="form-control  input-border-custom">
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.amt',1)}}({{{$currency}}})</label>
                     <input type="text" name = "amount" placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.amt',1)}}({{{$currency}}})" class="form-control input-border-custom input-border-custom" id="usr">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.cred',1)}}</label>
                     <input type="text" name = "credits" placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.cred',0)}}" class="form-control input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('description')}}</label>
                     <input type="text" name = "description" placeholder="{{trans_choice('admin.enter',0)}} {{trans('description')}}" class="form-control input-border-custom">
                  </div>
                  <button type="button" id = "add-credit-package-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.add_pack',1)}}</button>
               </div>
            </form>
            @endif
            

            <form action = "{{{ url('/admin/superpower_package/add') }}}"  method = "POST" id = "add-superpower-form">
               {!! csrf_field() !!}
               <div class="col-md-5 col-add-superpower">
                  <p class="add-credit-package-text">{{trans_choice('admin.add_super_pack',1)}}</p>
                  <div class="form-group">
                     <label class="package-label">{{trans_choice('admin.pack_name',1)}}</label>
                     <input type="text" name = "package_name" placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.pack_name',1)}}" class="form-control input-border-custom" id="usr">
                  </div>
                  <div class="form-group amount-us-credits">
                     <label class="package-label">{{trans_choice('admin.amt',1)}}({{{$currency}}})</label>
                     <input type="text" name = "amount" placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.amt',1)}}({{{$currency}}})" class="form-control input-border-custom " id="usr">
                  </div>
                  <div class="form-group amount-us-credits duration-col">
                     <label class="package-label package-label-duration">{{trans_choice('admin.duration',1)}}</label>
                     <input placeholder="{{trans_choice('admin.enter',0)}} {{trans_choice('admin.duration',1)}}" name = "duration" type="text" class="form-control input-border-custom">
                  </div>
                  <div class="form-group">
                     <label class="package-label">{{trans('description')}}</label>
                     <input type="text" name = "description" placeholder="{{trans_choice('admin.enter',0)}} {{trans('description')}}" class="form-control input-border-custom">
                  </div>
                  <button type="button" id = "add-superpower-btn" class="btn btn-info btn-addpackage btn-custom">{{trans_choice('admin.add_pack',1)}}</button>
               </div>
            </form>
            @if($credits_module_available == 'true')
            <div class="col-md-5 current-credit-col">
               <p class="current-credit-head">{{trans_choice('admin.curr_cred_pack',1)}}</p>
               <table class="table" cellspacing="0" cellpadding="0">
                  <thead>
                     <tr>
                        <th>{{trans_choice('admin.pack_name',1)}}</th>
                        <th>{{trans_choice('admin.amt',1)}}({{{$currency}}})</th>
                        <th>{{trans_choice('admin.cred',1)}}</th>
                        <th>{{trans_choice('admin.status',1)}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if(isset($packs))
                     @foreach ($packs as $pack)
                     <tr>
                        <td>{{{ $pack->packageName }}}</td>
                        <td>{{{ $pack->amount }}}</td>
                        <td>{{{ $pack->credits }}}</td>
                        
                        <td><label class="switch">
                           <input class="switch-input credits-switch" data-item-id="{{{ $pack->id }}}" type="checkbox" @if(!isset($pack->deleted_at)) checked @endif/>
                           <span class="switch-label" ></span> 
                           <span class="switch-handle"></span> 
                           </label>
                        </td>
                     </tr>
                     @endforeach    
                     @endif 
                  </tbody>
               </table>
            </div>
            @endif
            <div class="col-md-5 current-superpower-col">
               <p class="current-credit-head">{{trans_choice('admin.curr_super_pack',1)}}</p>
               <table class="table">
                  <thead>
                     <tr>
                        <th>{{trans_choice('admin.pack_name',1)}}</th>
                        <th>{{trans_choice('admin.amt',1)}}({{{$currency}}})</th>
                        <th>{{trans_choice('admin.duration',1)}}</th>
                        <th>{{trans_choice('admin.status',1)}}</th>
                     </tr>
                  </thead>
                  <tbody>
                     @if(isset($superPowerPacks))
                     @foreach ($superPowerPacks as $pack)
                     <tr>
                        <td>{{{ $pack->package_name }}}</td>
                        <td>{{{ $pack->amount }}}</td>
                        <td>{{{ $pack->duration }}}</td>
                        
                        <td>
                           <label class="switch">
                           <input class="switch-input superpower-switch" data-item-id="{{{ $pack->id }}}" type="checkbox" @if(!isset($pack->deleted_at)) checked @endif/>
                           <span class="switch-label" ></span> 
                           <span class="switch-handle"></span> 
                           </label>
                        </td>
                        
                     </tr>
                     @endforeach    
                     @endif 
                  </tbody>
               </table>
            </div>
         </div>
      </div>
   </section>
</div>
@endsection
@section('scripts')

<script>


$("#credits_module_available").on('change', function(){


   var checkbox = $(this);

   var credits_module_available = 'false';

   if ($(this).is(":checked")) {
      
      credits_module_available = 'true';
   } 



   $.post("{{{ url('/admin/credits/credits-module-available') }}}", 
      {_token:"{{{csrf_token()}}}", credits_module_available:credits_module_available}, function(reponse){
   
         if(reponse.status != 'success'){
   
            checkbox.prop('checked', !checkbox.prop("checked"));
         } 

         window.location.reload();
   
      });



});




$("#peoplenearby_only_superpowers").on('change', function(){


   var checkbox = $(this);

   var peoplenearby_only_superpowers = 'false';

   if ($(this).is(":checked")) {
      
      peoplenearby_only_superpowers = 'true';
   } 



   $.post("{{{ url('/admin/credits/peoplenearby-only-superpowers') }}}", 
      {_token:"{{{csrf_token()}}}", peoplenearby_only_superpowers:peoplenearby_only_superpowers}, function(reponse){
   
         if(reponse.status != 'success'){
   
            checkbox.prop('checked', !checkbox.prop("checked"));
         } 
   
      });



});


$("#spotlight_only_superpowers").on('change', function(){


   var checkbox = $(this);

   var spotlight_only_superpowers = 'false';

   if ($(this).is(":checked")) {
      
      spotlight_only_superpowers = 'true';
   } 



   $.post("{{{ url('/admin/credits/spotlight-only-superpowers') }}}", 
      {_token:"{{{csrf_token()}}}", spotlight_only_superpowers:spotlight_only_superpowers}, function(reponse){
   
         if(reponse.status != 'success'){
   
            checkbox.prop('checked', !checkbox.prop("checked"));
         } 
   
      });



});


$("#double-credit-superpower-btn").on('change', function(){


   var checkbox = $(this);

   var double_credits = 'false';

   if ($(this).is(":checked")) {
      
      double_credits = 'true';
   } 



   $.post("{{{ url('/admin/credits/double-credits-superpowers') }}}", 
      {_token:"{{{csrf_token()}}}", double_credits:double_credits}, function(reponse){
   
         if(reponse.status != 'success'){
   
            checkbox.prop('checked', !checkbox.prop("checked"));
         } 
   
      });



});





$('#add-credit-package-btn').click(function(){
   
  
      var data = $('#add-credit-package-form').serializeArray();
     
      $.post("{{{ url('/admin/credit_package/add') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
            $('#add-credit-package-form')[0].reset();
            setTimeout(function(){
              window.location.reload();
            }, 1000);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
});



$('#add-superpower-btn').click(function(){
   
  
      var data = $('#add-superpower-form').serializeArray();
     
      $.post("{{{ url('/admin/superpower_package/add') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
            $('#add-superpower-form')[0].reset();
            setTimeout(function(){
              window.location.reload();
            }, 1000);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
});



$('#set-riseup-credit-btn').click(function(){
   
  
      var data = $('#set-riseup-credit-form').serializeArray();
     
      $.post("{{{ url('admin/creditManage/riseupCred') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
});

$('#set-add-bullets-by-commands-btn').click(function(){


      var data = $('#set-add-bullets-by-commands-form').serializeArray();

      $.post("{{{ url('admin/creditManage/addBulletsCommands') }}}", data, function(reponse){

         if(reponse.status == 'success'){

            toastr.success(reponse.message);

         } else if(reponse.status == 'error'){

            toastr.error(reponse.message);
         }

      });

});




$('#set-spotlight-credit-btn').click(function(){
   
  
      var data = $('#set-spotlight-credit-form').serializeArray();
     
      $.post("{{{ url('admin/creditManage/spotCred') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
});

$('#set-credit-all-btn').click(function(){
   
  
      var data = $('#set-credit-all-form').serializeArray();
     
      $.post("{{{ url('admin/creditManage/credAddAll') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
            $('#set-credit-all-form')[0].reset();
             setTimeout(function(){
              window.location.reload();
            }, 1000);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
});





$('#set-default-credit-btn').click(function(){
   
  
      var data = $('#set-default-credit-form').serializeArray();
     
      $.post("{{{ url('admin/creditManage/defaultCredAdd') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
});


$('#set-default-super-power-btn').click(function(){
   
  
      var data = $('#set-default-super-power-form').serializeArray();
     
      $.post("{{{ url('admin/creditManage/defaultSuperPower') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
   
      });
   
});










$(".credits-switch").change(function(){

  var url = "{{{ url('/admin/credit_package') }}}";
  if(this.checked){
      url = url + "/activate/"+$(this).data('item-id');
  }
  else {
       url = url + "/deactivate/"+$(this).data('item-id');
  }

  $.get(url, function(reponse){

        if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
  });

 
});

$(".superpower-switch").change(function(){

  var url = "{{{ url('/admin/superpower_package') }}}";
  if(this.checked){
      url = url + "/activate/"+$(this).data('item-id');
  }
   else {
       url = url + "/deactivate/"+$(this).data('item-id');
  }

  $.get(url, function(reponse){

        if(reponse.status == 'success'){
   
            toastr.success(reponse.message);
   
         } else if(reponse.status == 'error'){
   
            toastr.error(reponse.message);
         }
  });
  

});




$("#set-currency-btn").click(function(){

  var data = $('#set-currency-form').serializeArray();

  $.post("{{{ url('/admin/creditManage/set/currency') }}}", data, function(reponse){
   
         if(reponse.status == 'success'){
   
            toastr.success('successfully currency saved');
   
         } else if(reponse.status == 'error'){
   
            toastr.error('failed to save currency');
         }
   
      });

});

</script>
@endsection