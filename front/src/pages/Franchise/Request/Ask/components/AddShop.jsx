import withToast from '../../../../../components/HOC/WithToastHOC.jsx';
import { useState } from 'react';
import {
  Button,
  TextField,
  ToggleButton,
  ToggleButtonGroup,
} from '@mui/material';
import Typography from '@mui/material/Typography';
import ShopImage from '../../../../../assets/images/Shop.jpg';
import { LocalizationProvider, TimePicker } from '@mui/x-date-pickers';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
import { useFranchiseRequest } from '../hooks/useFranchiseRequest.jsx';
import PlacesAutocomplete, {
  geocodeByAddress,
  getLatLng,
} from 'react-places-autocomplete';

const daysOfWeek = [
  'Monday',
  'Tuesday',
  'Wednesday',
  'Thursday',
  'Friday',
  'Saturday',
  'Sunday',
];

const mapOpeningHoursToDays = (openingHours) => {
  let mappedOpeningHours = [];
  Object.keys(openingHours).forEach((key, t) => {
    console.log('key', key);
    console.log('t', openingHours[key], openingHours);
    mappedOpeningHours.push({
      dow: t,
      startTime: openingHours[key],
      endTime: openingHours[key],
    });
  });

  return mappedOpeningHours;
};

const CreateShops = ({ handleNext, setToast }) => {
  const [label, setLabel] = useState('');
  const [address, setAddress] = useState('');
  const [openingHours, setOpeningHours] = useState('');
  const [openingDays, setOpeningDays] = useState('');
  const { franchiseRequest, checkLabel, onSave, checkAddress } =
    useFranchiseRequest();
  const handleDayChange = (event) => {
    setOpeningDays({
      ...openingDays,
      [event.target.name]: event.target.checked,
    });
  };
  const handleTimeChange = (name) => (time) => {
    setOpeningHours({ ...openingHours, [name]: time });
  };

  const handleAddressChange = (address) => {
    setAddress(address);
  };

  const handleSelect = (address) => {
    setAddress(address);
    geocodeByAddress(address)
      .then((results) => getLatLng(results[0]))
      .then((latLng) => console.log('Success', latLng))
      .catch((error) => console.error('Error', error));
  };
  const handleSubmit = () => {
    let error = checkLabel(label);
    error = checkAddress(address);

    if (error) {
      setToast({
        open: true,
        severity: 'error',
        message: error,
      });
      return;
    }

    onSave({
      shopLabel: label,
      shopAddress: address,
      openingHours: mapOpeningHoursToDays(openingHours),
      openingDays,
    });
    handleNext();
  };

  return (
    <>
      <LocalizationProvider dateAdapter={AdapterDateFns}>
        <Typography
          variant="h3"
          sx={{
            marginBottom: '20px',
          }}
        >
          Add your first shop to {franchiseRequest.franchiseLabel}
        </Typography>
        <img
          src={ShopImage}
          style={{
            width: '50%',
            height: 'auto',
            marginBottom: '20px',
          }}
        />
        <form onSubmit={handleSubmit} style={{ width: '80%' }}>
          <TextField
            label="Label"
            value={label}
            onChange={(e) => setLabel(e.target.value)}
            variant="outlined"
            margin="normal"
            fullWidth
          />
          <PlacesAutocomplete
            value={address}
            onChange={handleAddressChange}
            onSelect={handleSelect}
          >
            {({
              getInputProps,
              suggestions,
              getSuggestionItemProps,
              loading,
            }) => (
              <div>
                <TextField
                  {...getInputProps({
                    label: 'Address',
                    className: 'location-search-input',
                  })}
                  variant="outlined"
                  margin="normal"
                  fullWidth
                />
                <div className="autocomplete-dropdown-container">
                  {loading && <div>Loading...</div>}
                  {suggestions.map((suggestion) => {
                    const className = suggestion.active
                      ? 'suggestion-item--active'
                      : 'suggestion-item';
                    return (
                      <div
                        {...getSuggestionItemProps(suggestion, { className })}
                      >
                        <span>{suggestion.description}</span>
                      </div>
                    );
                  })}
                </div>
              </div>
            )}
          </PlacesAutocomplete>
          {daysOfWeek.map((day, key) => (
            <div
              key={day}
              style={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'space-between',
                marginBottom: '20px',
              }}
            >
              <ToggleButtonGroup
                value={openingDays[day] || false}
                exclusive
                onChange={(event, newValue) =>
                  handleDayChange({ target: { name: day, checked: newValue } })
                }
              >
                <ToggleButton value={true}>{day}</ToggleButton>
              </ToggleButtonGroup>
              <TimePicker
                label="Start Time"
                value={openingHours[`${day}Start`] || null}
                onChange={handleTimeChange(`${day}Start`)}
                disabled={!openingDays[day]}
              />
              <TimePicker
                label="End Time"
                value={openingHours[`${day}End`] || null}
                onChange={handleTimeChange(`${day}End`)}
                disabled={!openingDays[day]}
              />
            </div>
          ))}
          <Button
            type="submit"
            variant="contained"
            color="primary"
            onClick={handleSubmit}
          >
            Create Shop
          </Button>
        </form>
      </LocalizationProvider>
    </>
  );
};

const CreateShopsWithToast = withToast(CreateShops);
export { CreateShopsWithToast as CreateShops };
