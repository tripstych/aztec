import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import NavBar from "../../components/navbar";
import TopSection from "./topSection";
import BookingSteps from "./bookingSteps";
import { Marginer } from "../../components/marginer";
import AboutUs from "./aboutUs";
import { Footer } from "../../components/footer";
import CarbonBg from "../../components/assets/images/carbon_bg.jpg";
import Location from "./location";

const PageContainer = styled.div`
  ${tw`
  flex
  flex-col
  w-full
  h-full
  items-center
  `}
`;

const SectionTwoContainer = styled.div`
  ${tw`
    relative
    flex
    flex-col
    w-full
    items-center
    py-12
  `}
  background-image: url(${CarbonBg});
  background-size: cover;
  background-position: center 85%;
  background-repeat: no-repeat;

  &::before {
    content: "";
    ${tw`
      absolute
      top-0
      left-0
      w-full
      h-full
    `}
    background-color: rgba(0, 0, 0, 0.55); /* Dark tint with 50% opacity */
    z-index: 0; /* Ensure the overlay is below the content */
  }

  > * {
    z-index: 1; /* Ensure the content is above the overlay */
  }
`;

const SectionThreeContainer = styled.div`
  ${tw`
    relative
    flex
    flex-col
    w-full
    items-center
  `}
`;

const SectionSubtitle = styled.h3`
  ${tw`
    text-lg
    font-bold
    uppercase
    text-cBlue
    mt-6
    mb-4
    tracking-widest
  `}
`;

const SectionDescriptionTitle = styled.h3`
  ${tw`
    text-lg
    md:text-2xl
    font-bold
    uppercase
    text-white
    my-4
  `}
`;

const SectionTitle = styled.h2`
  ${tw`
    text-2xl
    md:text-6xl
    font-extrabold
    text-white
    tracking-wide
    mb-4
  `}
  text-align: center; /* Center the title text */
  line-height: 1.2; /* Optional for better spacing between lines */
`;

const Home = () => {
  return (
    <PageContainer>
      <NavBar />
      <TopSection />
      <Marginer direction="vertical" margin="4em" />
      <BookingSteps />
      <Marginer direction="vertical" margin="2em" />
      <SectionTwoContainer>
        <SectionSubtitle>What We Offer</SectionSubtitle>
        <SectionTitle>Fast & reliable Auto Glass Replacement</SectionTitle>
        <SectionDescriptionTitle>
          Guaranteed High Quality Results
        </SectionDescriptionTitle>
        <AboutUs />
      </SectionTwoContainer>
      <Marginer direction="vertical" margin="6em" />
      <SectionThreeContainer>
        <Location />
      </SectionThreeContainer>
      <Marginer direction="vertical" margin="6em" />
      <Footer />
    </PageContainer>
  );
};

export default Home;
