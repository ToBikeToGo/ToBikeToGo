import { Calendar } from '../../components/Calendar/Calendar';
import { useCalendar } from '../../components/Calendar/hooks/useCalendar.jsx';
import styled from 'styled-components';
import { Planning } from '../../components/Planning/Planning.jsx';
import { Button, TextField, useTheme } from '@mui/material';
import {
  Box,
  IconButton,
  Paper,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableFooter,
  TablePagination,
  TableRow,
} from '@mui/material';

import Typography from '@mui/material/Typography';

import React, { useEffect } from 'react';
import { TablePaginationActions } from '@mui/base';
import { VACATIONS_REQUEST_STATUS } from './constants/vacations.ts';
import { CheckCircle } from '@mui/icons-material';

const StyledPage = styled.div`
  display: flex;
  align-items: center;
  margin-top: 100px;
  margin: auto;
  justify-content: center;
  width: 100%;
`;

const StyledVacationBox = styled.div`
  background-color: #ffffff;
  display: flex;
  flex-direction: column;
  border-radius: 12px;
  box-shadow: 0px 4px 4px rgba(75, 158, 46, 0.25);
  padding: 2em;
  min-height: 500px;
  width: 30%;
  margin: 2rem;
  justify-content: center;
  align-items: center;
`;
const mockedEvents = [
  {
    id: 1,
    title: 'Vacation',
    start: new Date(2023, 9, 7, 10, 0, 0),
    end: new Date(2023, 9, 10, 10, 0, 0),
  },
];

const rows = [
  {
    description: 'Maternity leave',
    startDate: new Date(2021, 9, 7, 10, 0, 0),
    endDate: new Date(2021, 9, 10, 10, 0, 0),
    status: 'APPROVED',
  },
  {
    description: 'Vacation',
    startDate: new Date(2021, 9, 7, 10, 0, 0),
    endDate: new Date(2021, 9, 10, 10, 0, 0),
    status: 'APPROVED',
  },
];

const formatVacations = (vacations) => {
  return vacations.map((vacation) => ({
    id: vacation.id,
    title: vacation.description,
    start: new Date(vacation.startDate),
    end: new Date(vacation.endDate),
    status: vacation.status ? 'APPROVED' : 'PENDING',
  }));
};

function AskVacation() {
  const [vacations, setVacations] = React.useState([]);
  const [isLoaded, setIsLoaded] = React.useState(false);

  useEffect(() => {
    fetch('http://localhost:8888/api/vacations/user/162')
      .then((response) => response.json())
      .then((data) => {
        setVacations(formatVacations(data));
        setIsLoaded(true);
      });
  }, []);

  const { calendarRef, dates, isOpen, onChangeDate, handleOpen } = useCalendar({
    onChangeDateCallback: () => {
      console.log('dates', dates);
    },
  });

  const [page, setPage] = React.useState(0);
  const [rowsPerPage, setRowsPerPage] = React.useState(5);

  // Avoid a layout jump when reaching the last page with empty rows.
  const emptyRows =
    page > 0 ? Math.max(0, (1 + page) * rowsPerPage - rows.length) : 0;

  const handleChangePage = (event, newPage) => {
    setPage(newPage);
  };

  const handleChangeRowsPerPage = (event) => {
    setRowsPerPage(parseInt(event.target.value, 10));
    setPage(0);
  };

  return (
    <div className={'flex flex-col w-full '}>
      <StyledPage>
        <StyledVacationBox>
          <Calendar
            calendarRef={calendarRef}
            handleOpen={handleOpen}
            onChangeDate={onChangeDate}
            dates={dates}
            isOpen={isOpen}
          />
          <TextField
            id="outlined-basic"
            label="Description"
            variant="outlined"
            sx={{
              marginTop: '2em',
            }}
          />
          <Button
            variant="outlined"
            color="black"
            sx={{
              marginTop: '2em',
            }}
          >
            Ask Vacation
          </Button>
        </StyledVacationBox>

        <Planning events={vacations} />
      </StyledPage>
      <div>
        <Typography variant="h4" component="div" gutterBottom>
          Vacation requests
        </Typography>
        <TableContainer
          component={Paper}
          style={{
            width: '80%',
            margin: 'auto',
            borderRadius: '12px',
            boxShadow: '0px 4px 4px rgba(75, 158, 46, 0.25)',
            marginBottom: '2em',
          }}
        >
          <Table sx={{ minWidth: 500 }} aria-label="custom pagination table">
            <TableBody>
              {vacations.map((row) => (
                <TableRow key={row.name}>
                  <TableCell component="th" scope="row">
                    {row.title}
                  </TableCell>
                  <TableCell style={{ width: 160 }} align="right">
                    {row.start?.toString()}
                  </TableCell>
                  <TableCell style={{ width: 160 }} align="right">
                    {row.end?.toString()}
                  </TableCell>

                  <TableCell
                    style={{ width: 160, padding: '2em' }}
                    align="right"
                  >
                    {row.status === VACATIONS_REQUEST_STATUS.APPROVED ? (
                      <CheckCircle color={'success'} />
                    ) : (
                      'pending'
                    )}
                  </TableCell>
                </TableRow>
              ))}
              {emptyRows > 0 && (
                <TableRow style={{ height: 53 * emptyRows }}>
                  <TableCell colSpan={6} />
                </TableRow>
              )}
            </TableBody>
            <TableFooter>
              <TableRow>
                <TablePagination
                  rowsPerPageOptions={[5, 10, 25, { label: 'All', value: -1 }]}
                  colSpan={3}
                  count={rows.length}
                  rowsPerPage={rowsPerPage}
                  page={page}
                  SelectProps={{
                    inputProps: {
                      'aria-label': 'rows per page',
                    },
                    native: true,
                  }}
                  onPageChange={handleChangePage}
                  onRowsPerPageChange={handleChangeRowsPerPage}
                  ActionsComponent={TablePaginationActions}
                />
              </TableRow>
            </TableFooter>
          </Table>
        </TableContainer>{' '}
      </div>
    </div>
  );
}

export { AskVacation };
