import React, { useState } from 'react';
import {
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Avatar,
  IconButton,
  Box,
  Pagination,
} from '@mui/material';
import { Edit, Delete } from '@mui/icons-material';
import { CalendarIcon } from '@mui/x-date-pickers';
import { Link } from 'react-router-dom';
import { ShopListComponent } from '../../Shop/components/ShopList.jsx';

export const UsersTable = ({
  users,
  onMemberDelete,
  onMemberEdit,
  page,
  totalPage,
  onChangePage,
}) => {
  const [selectedUser, setSelectedUser] = useState(users[0]);

  return (
    <>
      <Box style={{ width: '100%', display: 'flex' }}>
        <TableContainer component={Paper}>
          <Table sx={{ minWidth: 850 }} aria-label="simple table">
            <TableHead>
              <TableRow>
                <TableCell>Avatar</TableCell>
                <TableCell>First Name</TableCell>
                <TableCell>Last Name</TableCell>
                <TableCell>Actions</TableCell>
              </TableRow>
            </TableHead>
            <TableBody>
              {users?.map((member) => (
                <TableRow
                  key={member.id}
                  sx={{ '&:last-child td, &:last-child th': { border: 0 } }}
                  onClick={() => setSelectedUser(member)}
                >
                  <TableCell component="th" scope="row">
                    <Avatar alt={member.firstname} src={member.avatar} />
                  </TableCell>
                  <TableCell>{member.firstname}</TableCell>
                  <TableCell>{member.lastname}</TableCell>
                  <TableCell>
                    <IconButton
                      aria-label="edit"
                      onClick={() => onMemberEdit(member.id)}
                    >
                      <Edit />
                    </IconButton>
                    <IconButton
                      aria-label="delete"
                      onClick={() => onMemberDelete(member.id)}
                    >
                      <Delete />
                    </IconButton>
                    <IconButton
                      aria-label="planning"
                      component={Link}
                      to={`/planning/user/${member.id}`}
                    >
                      <CalendarIcon />
                    </IconButton>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
        <Box
          className={'show-user-view'}
          style={{
            width: '50%',
            display: 'flex',
            flexDirection: 'column',
            backgroundColor: 'white',
            padding: '1em',
            marginLeft: '2em',
            borderRadius: '5px',
            alignItems: 'center',
          }}
        >
          {selectedUser && (
            <div className={'flex flex-col items-center '}>
              <h1>{selectedUser.firstname}</h1>
              <p>{selectedUser.lastname}</p>
              <p>{selectedUser.id}</p>
              <Avatar
                src={selectedUser.avatar}
                alt={selectedUser.firstname}
                sx={{ width: 200, height: 200 }}
              />
              <ShopListComponent shops={selectedUser?.shops} />
            </div>
          )}
        </Box>
      </Box>
      <div
        className={
          'p-5 px-8 rounded-xl bg-white shadow-xl  m-5 flex items-center justify-center '
        }
      >
        <Pagination
          count={totalPage}
          page={page}
          variant="outlined"
          onChange={onChangePage}
        />
      </div>
    </>
  );
};
