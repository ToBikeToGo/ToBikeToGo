import * as React from 'react';
import AppBar from '@mui/material/AppBar';
import Box from '@mui/material/Box';
import Toolbar from '@mui/material/Toolbar';
import IconButton from '@mui/material/IconButton';
import Typography from '@mui/material/Typography';
import Menu from '@mui/material/Menu';
import MenuIcon from '@mui/icons-material/Menu';
import Container from '@mui/material/Container';
import Avatar from '@mui/material/Avatar';
import Button from '@mui/material/Button';
import MenuItem from '@mui/material/MenuItem';
import AdbIcon from '@mui/icons-material/Adb';
import { Link, useNavigate } from 'react-router-dom';
import {
  Collapse,
  InputBase,
  List,
  ListItem,
  ListItemText,
  TextField,
} from '@mui/material';
import styled from 'styled-components';
import theme from '../../theme/theme.js';
import { Calendar } from '../Calendar/Calendar.jsx';
import { useCalendar } from '../Calendar/hooks/useCalendar.jsx';
import { useBookingContext } from '../../hooks/useBooking.jsx';
import { useUserContext } from '../../hooks/UserContext.jsx';
import { useTranslation } from '../../locales/hooks/getTranslation.js';
import { useEffect } from 'react';
import { ArrowBack, ExpandLess, ExpandMore } from '@mui/icons-material';
import NotificationBar from '../Notification/NotificationBar.jsx';

const Search = styled('div')(() => ({
  display: 'flex',
  position: 'relative',
  borderRadius: '10px',
  backgroundColor: `${theme.palette.common.white}`,
  '&:hover': {
    backgroundColor: 'white',
  },
  margin: '0 2% 0 10%',
  minWidth: '550px',
  padding: '0.5em 1em',
  border: `1px solid ${theme.palette.secondary.main}`,
}));

const SearchIconWrapper = styled('div')(({ theme }) => ({
  padding: 10,
  height: '100%',
  position: 'absolute',
  pointerEvents: 'none',
  display: 'flex',
  alignItems: 'center',
  justifyContent: 'center',
}));

const StyledInputBase = styled(InputBase)(({}) => ({
  color: 'inherit',
}));

function ResponsiveAppBar() {
  const [anchorElNav, setAnchorElNav] = React.useState(null);
  const [anchorElSubMenu, setAnchorElSubMenu] = React.useState(null);
  const [openSubMenu, setOpenSubMenu] = React.useState(false);

  const [setAnchorElUser] = React.useState(null);
  const navigate = useNavigate();
  const [open, setOpen] = React.useState(false);
  const {
    changeBookingDates,
    setSearchParams,
    searchParams,
    getAvailableBikes,
  } = useBookingContext();
  const { calendarRef, dates, isOpen, onChangeDate, handleOpen } = useCalendar({
    onChangeDateCallback: (d) => {
      changeBookingDates(d?.startDate, d?.endDate);
    },
  });

  const handleClickSubMenu = () => {
    setOpenSubMenu(!openSubMenu);
  };

  const { user, isFranchiseProvider, handleLogout, isLogged, isAdmin } =
    useUserContext();

  const searchRef = React.useRef(null);
  const [showSearchOptions, setShowSearchOptions] = React.useState(false);

  const handleSearchClick = () => {
    setShowSearchOptions(true);
  };

  const handleCLickOutsideSearch = (e) => {
    if (searchRef.current && !searchRef.current.contains(e.target)) {
      setShowSearchOptions(false);
    }
  };

  const handleSearch = async () => {
    navigate('/search-bikes');
    getAvailableBikes();
  };

  const onChangeInput = (e) => {
    setSearchParams((prev) => ({ ...prev, [e.target.name]: e.target.value }));
  };

  useEffect(() => {
    document.addEventListener('click', handleCLickOutsideSearch);
    return () => {
      document.removeEventListener('click', handleCLickOutsideSearch);
    };
  }, [handleCLickOutsideSearch]);

  const { getTranslation } = useTranslation();
  const [anchorEl, setAnchorEl] = React.useState(null);

  const pages = [
    {
      label: getTranslation('Navbar.rent.bike'),
      path: '/shops/map',
    },
    {
      label: getTranslation('Navbar.planning'),
      path: `/planning/${user.id}`,
    },
    {
      label: 'Last booking',
      path: '/last-booking',
    },
    {
      label: 'Shops',
      path: '/shops',
    },
  ];

  if (isAdmin) {
    pages.push({
      label: 'Admin',
      path: '/admin/users',
      isAdminRoute: true,
    });
  }

  const handleMenu = (event) => {
    setOpen(!open);

    setAnchorEl(event.currentTarget);
  };

  const handleClose = () => {
    setAnchorEl(null);
  };
  const handleOpenNavMenu = (event) => {
    setAnchorElNav(event.currentTarget);
  };

  const handleCloseNavMenu = () => {
    setAnchorElNav(null);
  };

  const handleOpenSubMenu = (event) => {
    setAnchorElSubMenu(event.currentTarget);
  };

  const handleCloseSubMenu = () => {
    setAnchorElSubMenu(null);
  };

  return (
    <AppBar
      position="static"
      sx={{
        backgroundColor: '#fff6f6',
        boxShadow: 'none',
      }}
    >
      <Container maxWidth="xl">
        <Toolbar disableGutters>
          <Typography
            variant="h2"
            noWrap
            component="a"
            href="#app-bar-with-responsive-menu"
            sx={{
              mr: 2,
              display: { xs: 'none', md: 'flex' },
              fontWeight: 700,
              textDecoration: 'none',
              // Add these lines
              overflow: 'visible', // Make sure the logo is not cut off
              whiteSpace: 'normal', // Allow the logo to wrap to the next line if necessary
            }}
          >
            BikeToGo
          </Typography>

          <Box sx={{ flexGrow: 1, display: { xs: 'flex', md: 'none' } }}>
            <IconButton
              size="large"
              aria-label="account of current user"
              aria-controls="menu-appbar"
              aria-haspopup="true"
              onClick={handleOpenNavMenu}
              color="inherit"
            >
              <MenuIcon />
            </IconButton>
            <Menu
              id="menu-appbar"
              anchorEl={anchorElNav}
              anchorOrigin={{
                vertical: 'bottom',
                horizontal: 'left',
              }}
              keepMounted
              transformOrigin={{
                vertical: 'top',
                horizontal: 'left',
              }}
              open={Boolean(anchorElNav)}
              onClose={handleCloseNavMenu}
              sx={{
                display: { xs: 'block', md: 'none' },
              }}
            >
              {pages.map(({ label, isAdminRoute = false }) => (
                <MenuItem key={label} onClick={handleCloseNavMenu}>
                  <Typography
                    textAlign="center"
                    style={{
                      color: isAdminRoute ? 'red' : 'black',
                    }}
                  >
                    {label}
                  </Typography>
                </MenuItem>
              ))}
            </Menu>
          </Box>
          <AdbIcon sx={{ display: { xs: 'flex', md: 'none' }, mr: 1 }} />
          <Typography
            variant="h5"
            noWrap
            component="a"
            href="#app-bar-with-responsive-menu"
            sx={{
              mr: 2,
              display: { xs: 'flex', md: 'none' },
              flexGrow: 1,
              fontFamily: 'monospace',
              fontWeight: 700,
              letterSpacing: '.3rem',
              color: 'inherit',
              textDecoration: 'none',
            }}
          >
            BikeToGo
          </Typography>
          <Box sx={{ flexGrow: 1, display: { xs: 'none', md: 'flex' } }}>
            {pages.map(({ label, path }) => (
              <Button
                key={path}
                onClick={handleCloseNavMenu}
                sx={{
                  my: 2,
                  color: theme.palette.text.secondary,
                  display: 'block',
                }}
                component={Link}
                to={path}
              >
                {label}
              </Button>
            ))}
          </Box>
          <Search ref={searchRef}>
            <StyledInputBase
              placeholder="Find a bike…"
              inputProps={{ 'aria-label': 'search' }}
              onClick={handleSearchClick}
              onChange={onChangeInput}
              onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
              name={'label'}
            />
            <Box
              sx={{
                display: 'flex',
                alignItems: 'center',
                justifyContent: 'center',
                position: 'relative',
                width: '100%',
                flexDirection: 'column',
              }}
            >
              <Calendar
                calendarRef={calendarRef}
                handleOpen={handleOpen}
                onChangeDate={onChangeDate}
                dates={dates}
                isOpen={isOpen}
              />
              {showSearchOptions && (
                <div
                  style={{
                    margin: '1em',
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'center',
                    opacity: showSearchOptions ? 1 : 0,
                    transition: 'opacity 1s ease-in-out',
                  }}
                  className="search-options"
                >
                  <TextField
                    label="Max Price"
                    type="number"
                    InputLabelProps={{
                      shrink: true,
                    }}
                    sx={{ m: 2 }}
                    name="maxPrice"
                    onChange={onChangeInput}
                    onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                  />
                  <TextField
                    label="Brand"
                    type="text"
                    InputLabelProps={{
                      shrink: true,
                    }}
                    name="brand"
                    onChange={onChangeInput}
                    onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                  />
                  <TextField
                    label="City"
                    type="text"
                    InputLabelProps={{
                      shrink: true,
                    }}
                    sx={{ m: 2 }}
                    name="city"
                    onChange={onChangeInput}
                    onKeyDown={(e) => e.key === 'Enter' && handleSearch()}
                  />
                </div>
              )}
            </Box>

            <SearchIconWrapper></SearchIconWrapper>
          </Search>
          <Box sx={{ flexGrow: 0 }}>
            {' '}
            {isLogged ? (
              <div className="flex items-centerƒsea">
                <Button
                  onClick={handleMenu}
                  variant="outlined"
                  id="fade-button"
                  aria-controls={open ? 'fade-menu' : undefined}
                  aria-haspopup="true"
                  aria-expanded={open ? 'true' : undefined}
                  style={{
                    display: 'flex',
                    alignItems: 'center',
                    justifyContent: 'end',
                    width: '250px',
                    backgroundColor: 'white',
                  }}
                >
                  <p class="text-center ml-2 mr-2 ">
                    {user.firstname} {user.lastname}
                  </p>
                  <Avatar alt={user.firstname} src={user.avatar} />{' '}
                  {!open ? (
                    <ExpandMore />
                  ) : (
                    <ExpandMore
                      sx={{
                        transform: 'rotate(180deg)',
                      }}
                    />
                  )}
                </Button>
                <NotificationBar />
              </div>
            ) : (
              <Button
                variant="contained"
                component={Link}
                to="/login"
                sx={{
                  width: '250px',
                  backgroundColor: 'white',
                }}
              >
                Me connecter
              </Button>
            )}
            <Menu
              id="menu-appbar"
              anchorEl={anchorEl}
              keepMounted
              open={Boolean(anchorEl)}
              onClose={handleClose}
            >
              <MenuItem
                onClick={handleClose}
                component={Link}
                to={`/user/edit-profile/me`}
                sx={{
                  width: '250px',
                }}
              >
                Edit Profile
              </MenuItem>
              {isFranchiseProvider && (
                <MenuItem onClick={handleClose} component={Link} to="/my-shops">
                  My Shops
                </MenuItem>
              )}
              {isAdmin && (
                <>
                  <MenuItem onClick={handleClickSubMenu}>
                    Admin {openSubMenu ? <ExpandLess /> : <ExpandMore />}
                  </MenuItem>
                  <Collapse in={openSubMenu} timeout="auto" unmountOnExit>
                    <List
                      component="div"
                      disablePadding
                      style={{
                        backgroundColor: '#ebf6d9',
                      }}
                    >
                      <ListItem button component={Link} to="/admin/users">
                        <ListItemText primary="Users" />
                      </ListItem>
                      <ListItem
                        button
                        component={Link}
                        to="/franchise/request/validate"
                      >
                        <ListItemText primary="Franchise requests" />
                      </ListItem>
                    </List>
                  </Collapse>
                </>
              )}
              {!isFranchiseProvider ? (
                <MenuItem
                  onClick={handleClose}
                  component={Link}
                  to="/franchise/request"
                >
                  Rejoindre l'aventure
                </MenuItem>
              ) : (
                <>
                  <MenuItem
                    onClick={handleClose}
                    component={Link}
                    to="/my-franchise"
                  >
                    Mes franchises
                  </MenuItem>
                </>
              )}

              <MenuItem onClick={handleLogout} component={Link} to="">
                Logout
              </MenuItem>

              <MenuItem
                onClick={handleClose}
                component={Link}
                to="/my-shops"
              ></MenuItem>
            </Menu>
          </Box>
        </Toolbar>
      </Container>
    </AppBar>
  );
}
export default ResponsiveAppBar;
