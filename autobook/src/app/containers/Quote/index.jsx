import React from "react";
import styled from "styled-components";
import tw from "twin.macro";
import NavBar from "../../components/navbar";
import { Marginer } from "../../components/marginer";
import { Footer } from "../../components/footer";
import StepOne from "../../components/steps/StepOne";
import GarageBg from "../../components/assets/images/garage_bg.jpg";
import CarbonBg from "../../components/assets/images/carbon_bg.jpg";
import WindshieldBg from "../../components/assets/images/chip.jpg";
import StepTwo from "../../components/steps/StepTwo";
import StepThree from "../../components/steps/StepThree";
import StepFour from "../../components/steps/StepFour";

const PageContainer = styled.div`
  ${tw`
  flex
  flex-col
  w-full
  h-full
  items-center
  `}
`;

const SectionContainer = styled.div`
  ${tw`
    relative
    flex
    flex-col
    w-full
    items-center
    py-12
    bg-gray-100
  `}
`;

const SectionLandingContainer = styled.div`
  ${tw`
    relative
    flex
    flex-col
    w-full
    items-center
    justify-center
    py-12
    min-h-full
    md:min-h-[85vh]
  `}/* background-image: url(${WindshieldBg});
  background-size: cover; 
  background-position: center; 
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
    background-color: rgba(0, 0, 0, 0.55); 
    z-index: 0; 
  }

  > * {
    z-index: 1; 
    ${tw`
      text-center
    `}
  } */
`;

const SectionOneContainer = styled.div`
  ${tw`
    relative
    flex
    flex-col
    w-full
    items-center
    justify-center
    py-12
  `}
  background-image: url(${GarageBg});
  background-size: cover;
  background-position: center;
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

const StepSubtitle = styled.h3`
  ${tw`
    text-xl
    font-bold
    uppercase
    text-cBlue
    mb-2
    tracking-widest
  `}
`;

const SectionTitle = styled.h2`
  ${tw`
    text-3xl
    md:text-5xl
    font-bold
    text-white
  `}
  line-height: 1.2;
`;

const SectionContent = styled.p`
  ${tw`
    text-sm
    md:text-lg
    font-semibold
    text-gray-500
    max-w-4xl
    text-center
    mt-8
  `}
`;

const Quote = () => {
  return (
    <PageContainer>
      <NavBar />
      <Marginer direction="vertical" margin="4em" />
      <SectionLandingContainer>
        <h2 className="text-3xl md:text-9xl tracking-wide font-extrabold text-cBlue mb-4">
          Auto Glass Repair
        </h2>
        <h3 className="text-2xl md:text-7xl tracking-wide font-extrabold text-black mt-4">
          Online Booking Service
        </h3>
      </SectionLandingContainer>
      <SectionOneContainer>
        <StepSubtitle>Step 01</StepSubtitle>
        <SectionTitle>Choose Your Car Type</SectionTitle>
        <StepOne />
      </SectionOneContainer>
      <SectionContainer>
        <StepSubtitle>Step 02</StepSubtitle>
        <h2 className="text-3xl md:text-5xl font-bold text-black leading-5">
          Specify The Glass
        </h2>
        <SectionContent>Which Glass Needs Replacement</SectionContent>
        <StepTwo />
      </SectionContainer>
      <SectionThreeContainer>
        <StepSubtitle>Step 03</StepSubtitle>
        <SectionTitle>Date and Time</SectionTitle>
        <StepThree />
      </SectionThreeContainer>
      <SectionContainer>
        <StepSubtitle>Step 04</StepSubtitle>
        <h2 className="text-3xl md:text-5xl font-bold text-black leading-5">
          Summary
        </h2>
        <StepFour />
      </SectionContainer>
      <Marginer direction="vertical" margin="8em" />
      <Footer />
    </PageContainer>
  );
};

export default Quote;
