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
import { MyFranchise } from './pages/Shop/MyShops/MyFranchise.jsx';
import { ShopForOwner } from './pages/Shop/ShopForOwner.jsx';
import { AddMemberPage } from './pages/Shop/MyShops/AddMember.jsx';
import { EditMember } from './pages/Shop/MyShops/EditMember.jsx';
import { EditProfile } from './pages/User/EditProfile.jsx';
import { SearchBikes } from './pages/Bikes/SearchBikes.jsx';
import { CreateBikePage } from './pages/Bikes/CreateBike.jsx';
import { EditBike } from './pages/Bikes/EditBike.jsx';
import { ShopStats } from './pages/Stats/ShopStats.jsx';
import { EditShop } from './pages/Shop/components/EditShop.jsx';
import { ShopPlanningByUser } from './pages/Planning/ShopPlanningByUser.jsx';
import { ShopBookingPlannings } from './pages/Planning/ShopBookingPlannings.jsx';
import { ManageUsers } from './pages/Admin/ManageUsers.jsx';
import { CheckSecurityMiddleware } from './components/Security/SecurityMiddleware.jsx';
const StyledApp = styled.div`
  background-color: #fff6f6;
  min-height: 100vh;
  display: flex;
  flex-direction: column;
  align-items: center;
`;
function App() {
  return (
    <>
      <BrowserRouter>
        <ThemeProvider theme={theme}>
          <StyledApp>
            <UserProvider>
              <ShopProvider>
                <BookingProvider>
                  <VacationProvider>
                    <CheckSecurityMiddleware>
                      <Navbar />

                      <Routes>
                        <Route exact path="/" element={<Home />} />
                        <Route exact path="/login" element={<Login />} />
                        <Route exact path="/register" element={<Register />} />
                        <Route
                          exact
                          path="/admin/users"
                          element={<ManageUsers />}
                        />
                        <Route
                          path="/rent/bike/:bikeId"
                          element={<RentABike />}
                        />
                        <Route
                          exact
                          path="/my-planning"
                          element={<MyPlanning />}
                        />{' '}
                        <Route
                          exact
                          path="/planning/:userId"
                          element={<MyPlanning />}
                        />
                        <Route
                          exact
                          path="/vacations-request/:shopId"
                          element={<VacationsRequestList />}
                        />
                        <Route
                          exact
                          path="/planning/user/:userId"
                          element={<MyPlanning isUser={false} />}
                        />
                        <Route
                          exact
                          path="/ask-vacation"
                          element={<AskVacation />}
                        />
                        <Route
                          exact
                          path="/last-booking"
                          element={<LastBooking />}
                        />
                        <Route
                          exact
                          path="/shop-booking-planning/:shopId"
                          element={<ShopBookingPlannings />}
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
                          path={'/shop/stats/:shopId'}
                          element={<ShopStats />}
                        />{' '}
                        <Route
                          exact
                          path={'/shop/:shopId/edit'}
                          element={<EditShop />}
                        />
                        <Route
                          exact
                          path={'/franchise/request/validate'}
                          element={<ListRequest />}
                        />
                        <Route
                          path="/bikes/:shopId"
                          element={<BikesByShop />}
                        />
                        <Route
                          path="/bikes/edit/:bikeId"
                          element={<EditBike />}
                        />
                        <Route
                          path="/create-bike/:shopId"
                          element={<CreateBikePage />}
                        />
                        <Route path="/search-bikes" element={<SearchBikes />} />
                        <Route path="/my-franchise" element={<MyFranchise />} />
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
                          path="/user/edit-profile/me"
                          element={<EditProfile />}
                        />
                        <Route
                          path="/my-shops/:shopId/members"
                          element={<ShopForOwner />}
                        />
                        <Route path="*" element={<h1>404</h1>} />
                      </Routes>
                    </CheckSecurityMiddleware>
                  </VacationProvider>

                  <Footer />
                </BookingProvider>
              </ShopProvider>
            </UserProvider>
          </StyledApp>
        </ThemeProvider>
      </BrowserRouter>
    </>
  );
}

export default App;
