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
import { ShopProvider } from './hooks/UseShop.jsx';
import { VacationsRequestList } from './pages/Planning/VacationsRequestList.jsx';
import { VacationProvider } from './pages/Planning/hooks/useVacation.jsx';
import { BikesByShop } from './pages/Bikes/BikesByShop.jsx';
import { ShopList } from './pages/Shop/ShopList.jsx';
import { ShopsMapView } from './pages/Shop/ShopsMapView.jsx';
import { BookingProvider } from './hooks/useBooking.jsx';
import Register from './pages/Register/Register.jsx';
import { FranchiseRequest } from './pages/Franchise/Request/Ask/FranchiseRequestStepper.jsx';
import { ListRequest } from './pages/Franchise/Request/Validate/List.jsx';
import { MyShops } from './pages/Shop/MyShops/MyShops.jsx';
import { ShopForOwner } from './pages/Shop/ShopForOwner.jsx';
import { AddMemberPage } from './pages/Shop/MyShops/AddMember.jsx';
import { EditMember } from './pages/Shop/MyShops/EditMember.jsx';
import { EditProfile } from './pages/User/EditProfile.jsx';
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
          <UserProvider>
            <ShopProvider>
              <BookingProvider>
                <VacationProvider>
                  <BrowserRouter>
                    <Navbar />

                    <Routes>
                      <Route exact path="/" element={<Home />} />
                      <Route exact path="/login" element={<Login />} />
                      <Route exact path="/register" element={<Register />} />
                      <Route
                        path="/rent/bike/:bikeId"
                        element={<RentABike />}
                      />
                      <Route
                        exact
                        path="/my-planning"
                        element={<MyPlanning />}
                      />
                      <Route
                        path="/vacations-request/:shopId"
                        element={<VacationsRequestList />}
                      />
                      <Route
                        exact
                        path="/planning/:shopId"
                        element={<MyPlanning />}
                      />
                      <Route
                        exact
                        path="/ask-vacation"
                        element={<AskVacation />}
                      />
                      <Route
                        exact
                        path="/last-booking/:shopId"
                        element={<LastBooking />}
                      />
                      <Route path="/shops" element={<ShopList />} />
                      <Route path="/shops/map" element={<ShopsMapView />} />
                      <Route
                        exact
                        path={'/franchise/:franchiseId/shops'}
                        element={<FranchiseShopsList />}
                      />
                      <Route
                        exact
                        path={'/franchise/request/'}
                        element={<FranchiseRequest />}
                      />
                      <Route
                        exact
                        path={'/franchise/request/validate'}
                        element={<ListRequest />}
                      />
                      <Route path="/bikes/:shopId" element={<BikesByShop />} />
                      <Route path="/my-shops" element={<MyShops />} />
                      <Route
                        path="/my-shops/add-member/:shopId"
                        element={<AddMemberPage />}
                      />{' '}
                      <Route
                        path="/my-shops/edit-member/:userId"
                        element={<EditMember />}
                      />
                      <Route
                        path="/user/edit-profile/:userId"
                        element={<EditProfile />}
                      />
                      <Route
                        path="/my-shops/:shopId"
                        element={<ShopForOwner />}
                      />
                      <Route path="*" element={<h1>404</h1>} />
                    </Routes>
                  </BrowserRouter>
                </VacationProvider>

                <Footer />
              </BookingProvider>
            </ShopProvider>
          </UserProvider>
        </StyledApp>
      </ThemeProvider>
    </>
  );
}

export default App;
