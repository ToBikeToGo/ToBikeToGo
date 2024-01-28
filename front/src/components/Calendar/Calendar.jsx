import { TextField } from '@mui/material';
import { addDays, format } from 'date-fns';
import { DateRange } from 'react-date-range';
import theme from '../../theme/theme.js';
import { useCalendar } from './hooks/useCalendar.jsx';

const Calendar = ({
  calendarRef,
  onChangeDate,
  handleOpen,
  dates,
  isOpen,
  disabledDateCallback,
}) => {
  return (
    <>
      <div className={'flex justify-center mt-4 ba'} style={{}}>
        <TextField
          InputProps={{
            color: 'primary',
          }}
          label={'Start date'}
          size={'small'}
          color={'primary'}
          value={format(dates[0]?.startDate, 'dd/MM/yyyy')}
          mb={2}
          onClick={handleOpen}
          sx={{
            marginRight: '2em',
          }}
        />
        <TextField
          size={'small'}
          label={'End date'}
          variant="outlined"
          value={format(dates[0]?.endDate, 'dd/MM/yyyy')}
          mb={2}
          onClick={handleOpen}
        />
      </div>

      {isOpen && (
        <DateRange
          disabledDay={(date) => {
            disabledDateCallback?.();
            // not weekend
            return date.getDay() === 0 || date.getDay() === 6;

            //TODO disable non working days
          }}
          onChange={onChangeDate}
          showSelectionPreview={true}
          moveRangeOnFirstSelection={false}
          months={2}
          ranges={dates}
          direction="horizontal"
          rangeColors={[theme.palette.secondary.main, '#FF8E53']}
        />
      )}
    </>
  );
};

export { Calendar };
