<?php
/**
 * Example: Location Page with Dynamic Countries
 * Use this as reference for your location page
 */

// Load CodeIgniter
require_once 'index.php';

// In your controller, load the helper if not autoloaded
$this->load->helper('countries');

// Get countries array
$countries = get_countries();

// Or use in view directly
?>

<!-- Example 1: Dropdown using helper function -->
<select name="country" class="form-control">
    <?php echo get_countries_dropdown($selected_country); ?>
</select>

<!-- Example 2: Manual loop -->
<select name="country" class="form-control">
    <option value="">Select Country</option>
    <?php foreach (get_countries() as $code => $name): ?>
        <option value="<?php echo html_escape($code); ?>" 
                <?php echo ($selected_country == $code) ? 'selected' : ''; ?>>
            <?php echo html_escape($name); ?>
        </option>
    <?php endforeach; ?>
</select>

<!-- Example 3: In Controller - pass to view -->
<?php
// In your controller:
$data['countries'] = get_countries();
$data['selected_country'] = $this->input->post('country'); // or from database
$this->load->view('location_page', $data);
?>

