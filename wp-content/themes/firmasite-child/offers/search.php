<?php
/**
 * Implemantation of search functionality for CECommunity Offers Component
 *
 * @author Chris P.
 */
?>

<br>

<div class="panel panel-default">
    <div class="panel-body">
        <div class="page">

            <!-- Hidden Fields for Offer Sectors covered-->   
            <input type="hidden" class="form-control" name="offer-sectors" id="offer-sectors" value=""/>
            <!-- End of Hidden Fields -->

            <div class="col-xs-12 col-md-3">
                <label for="offer-type"><?php _e('Type of offer', 'firmasite'); ?></label>
                <select name="offer-type" id="offer-type">
                    <option value="none"  selected="selected">ALL</option>
                    <?php
                    //Fetch Grant Programs form DB
                    $results = BP_Offer::getOfferTypes();
                    if (is_array($results)) {
                        foreach ($results as $offer_type) {
                            echo "<option value = '{$offer_type->id }'>{$offer_type->description}</option>";
                        }
                    }
                    ?>
                </select>
                <br/>
            </div>
        </div>
    </div>
</div>


