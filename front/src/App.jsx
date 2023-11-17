import './App.css';
import { BrowserRouter, Route, Routes } from 'react-router-dom';
import Login from './pages/Login/Login.jsx';
import Navbar from './components/Navbar/Navbar.jsx';
import { ThemeProvider } from '@mui/material/styles';
import { RentABike } from './pages/Planning/RentABike.jsx';
import theme from './theme/theme.js';
import styled from 'styled-components';
import { Home } from './pages/Home/Home.jsx';
import { Footer } from './components/Footer/Footer.jsx';
import { MyPlanning } from './pages/Planning/MyPlanning.jsx';
import 'react-big-calendar/lib/css/react-big-calendar.css';
import { AskVacation } from './pages/Planning/AskVacation.jsx';
import { LastBooking } from './pages/Booking/LastBooking.jsx';
import { UserProvider } from './hooks/UserContext.jsx';
import { FranchiseShopsList } from './pages/Franchise/FranchiseShopsList.jsx';
const StyledApp = styled.div`
  background-color: ${theme.palette.background.default};
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
`;
function App() {
  return (
    <>
      <ThemeProvider theme={theme}>
        <StyledApp>
          <Navbar />
          <UserProvider>
            <BrowserRouter>
              <Routes>
                <Route exact path="/" element={<Home />} />
                <Route exact path="/login" element={<Login />} />
                <Route exact path="/rent/bike" element={<RentABike />} />
                <Route exact path="/my-planning" element={<MyPlanning />} />
                <Route
                  exact
                  path="/planning/:shopId"
                  element={<MyPlanning />}
                />

                <Route exact path="/ask-vacation" element={<AskVacation />} />
                <Route exact path="/last-booking" element={<LastBooking />} />
                <Route
                  exact
                  path="/last-booking/:shopId"
                  element={<LastBooking />}
                />
                <Route
                  exact
                  path={'/franchise/:franchiseId/shops'}
                  element={<FranchiseShopsList />}
                />
                <Route path="*" element={<h1>404</h1>} />
              </Routes>
            </BrowserRouter>
            <Footer />
          </UserProvider>
        </StyledApp>
      </ThemeProvider>
    </>
  );
}

export default App;
