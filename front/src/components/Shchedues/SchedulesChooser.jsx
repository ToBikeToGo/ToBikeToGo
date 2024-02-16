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
  let dayOfWeek = 0;
  for (let i = 0; i < Object.keys(openingHours).length; i += 2) {
    const startKey = Object.keys(openingHours)[i];
    const endKey = Object.keys(openingHours)[i + 1];
    mappedOpeningHours.push({
      dow: dayOfWeek,
      startTime: openingHours[startKey],
      endTime: openingHours[endKey],
    });
    dayOfWeek++;
  }

  return mappedOpeningHours;
};

export const SchedulesChooser = ({ onChange }) => {
  const [openingHours, setOpeningHours] = useState('');
  const [openingDays, setOpeningDays] = useState('');

  const handleDayChange = (event) => {
    console.log(event.target);
    setOpeningDays({
      ...openingDays,
      [event.target.name]: event.target.checked,
    });

    onChange(mapOpeningHoursToDays(openingHours));
  };

  const handleTimeChange = (key) => (date) => {
    setOpeningHours((prevOpeningHours) => {
      const updatedOpeningHours = {
        ...prevOpeningHours,
        [key]: date,
      };

      onChange(mapOpeningHoursToDays(updatedOpeningHours));

      return updatedOpeningHours;
    });
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
