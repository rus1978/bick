<div class="contact">
    <form action="/" method="post">
	    <label class="glob-publish">Publish my contact <input type="checkbox" name="published"[+contact-publish-checked+]></label>
            
        <h2 class="main-title">My Contact</h2>
        <div class="form">
    
                <div class="col">
                    <h6 class="title">Contact</h6>
                    
                    <label class="row">Firstname:<input type="text" name="firstname" class="inptext" value="[+firstname-value+]"></label>
                    <label class="row">Lastname:<input type="text" name="lastname" class="inptext" value="[+lastname-value+]"></label>
                    <label class="row">Address:<input type="text" name="address" class="inptext" value="[+address-value+]"></label>
                    <label class="row">ZIP/City:<input type="text" name="city" class="inptext" value="[+city-value+]"></label>
                    <label class="row">Country:
                        <span class="selectwrap">
                            <select name="country_id">
                                <option value="0"></option>
                                [+country-sel-options+]
                            </select>
                        </span>
                    </label>
        
                </div>
                
                [+wrapper+]
                
                <div class="subm-wrap">
                    <input type="submit" class="button" value="Save">
                </div>
        
        </div>
	</form>
</div>