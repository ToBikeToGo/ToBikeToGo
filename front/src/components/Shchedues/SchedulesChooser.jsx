import { ToggleButton, ToggleButtonGroup } from '@mui/material';
import { useState } from 'react';
import { LocalizationProvider, TimePicker } from '@mui/x-date-pickers';
import { AdapterDateFns } from '@mui/x-date-pickers/AdapterDateFns';
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

export const SchedulesChooser = ({ onChange }) => {
  const [openingHours, setOpeningHours] = useState('');
  const [openingDays, setOpeningDays] = useState('');

  const handleDayChange = (event) => {
    setOpeningDays({
      ...openingDays,
      [event.target.name]: event.target.checked,
    });

    onChange(mapOpeningHoursToDays(openingHours));
  };

  const handleTimeChange = (key) => (date) => {
    setOpeningHours({
      ...openingHours,
      [key]: date,
    });

    onChange(mapOpeningHoursToDays(openingHours));
  };

  return (
    <LocalizationProvider dateAdapter={AdapterDateFns}>
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
    </LocalizationProvider>
  );
};
