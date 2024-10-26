import { ToggleButton, ToggleButtonGroup } from '@mui/material';
import { useState, useEffect } from 'react';
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
  
  daysOfWeek.forEach((day, index) => {
    const startKey = `${day}Start`;
    const endKey = `${day}End`;

    if (openingHours[startKey] && openingHours[endKey]) {
      const startTime = new Date(openingHours[startKey]);
      startTime.setHours(startTime.getHours() + 2);

      const endTime = new Date(openingHours[endKey]);
      endTime.setHours(endTime.getHours() + 2);

      mappedOpeningHours.push({
        dow: index,
        startTime: startTime,
        endTime: endTime,
      });
    }
  });

  return mappedOpeningHours;
};

export const SchedulesChooser = ({ onChange, initialSchedules }) => {
  const [openingHours, setOpeningHours] = useState({});
  const [openingDays, setOpeningDays] = useState({});

  useEffect(() => {
    if (initialSchedules && initialSchedules.length > 0) {
      initialSchedules.forEach((schedule) => {
        const day = daysOfWeek[schedule.dow];
        setOpeningDays((prev) => ({ ...prev, [day]: true }));
        setOpeningHours((prev) => ({
          ...prev,
          [`${day}Start`]: schedule.startTime,
          [`${day}End`]: schedule.endTime,
        }));
      });
    }
  }, [initialSchedules]);

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
