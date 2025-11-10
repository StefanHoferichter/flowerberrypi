        	<table border="1" cellpadding="5" cellspacing="0" style="margin-left: auto; margin-right: 0;">
                        <tr>
              <td> 
<form action="{{ $form_url }}" method="get">
    <label for="time_horizon_days">Choose Time Horizon:</label>
    <select name="time_horizon_days" id="time_horizon_days">
        <option value="1" {{ request('time_horizon_days', 3) == 1 ? 'selected' : '' }}>1 day</option>
        <option value="2" {{ request('time_horizon_days', 3) == 2 ? 'selected' : '' }}>2 days</option>
        <option value="3" {{ request('time_horizon_days', 3) == 3 ? 'selected' : '' }}>3 days</option>
        <option value="5" {{ request('time_horizon_days', 3) == 5 ? 'selected' : '' }}>5 days</option>
        <option value="10" {{ request('time_horizon_days', 3) == 10 ? 'selected' : '' }}>10 days</option>
    </select>
    <button type="submit">Submit</button>
</form>
</td>
                </tr>
                </tbody>
            </table>        

