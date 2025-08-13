import React, { useState } from "react";
import styled from "styled-components";
import tw from "twin.macro";
import "swiper/css";
import "swiper/css/navigation";
import "swiper/css/pagination";
import { faClock } from "@fortawesome/free-regular-svg-icons";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import CarModelCanvas from "../../components/models/carModel";
import { useNavigate } from "react-router-dom";

const SectionContainer = styled.div`
  ${tw`
    flex
    flex-col
    w-full
    py-12
    text-black
    items-center
  `}
`;

const ContentWrapper = styled.div`
  ${tw`
    flex
    flex-col
    lg:flex-row
    items-center
    justify-between
    w-3/4
  `}
`;

const ModelContainer = styled.div`
  ${tw`
    relative
    w-full
    lg:w-3/4
    rounded-t-xl
    md:rounded-l-xl
    md:rounded-r-none
  `}
`;

const ArrowButton = styled.button`
  ${tw`
    mx-4
    p-4
    bg-cBlue
    text-white
    font-semibold
    rounded-full
    shadow-md
    hover:bg-blue-700
    transition-all
    duration-300
  `}
  width: 60px;
  height: 60px;
  display: flex;
  align-items: center;
  justify-content: center;
`;

const DescriptionContainer = styled.div`
  ${tw`
    w-full
    lg:w-1/3
    lg:mt-0
    p-8
    md:p-14
    flex
    flex-col
    text-center
    md:text-left
    md:h-full
    rounded-b-xl
    md:rounded-r-xl
    md:rounded-l-none
  `}
  background-color: white;
  box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
`;

const Title = styled.h2`
  ${tw`
    text-2xl
    md:text-4xl
    font-extrabold
    text-black
  `}
`;

const TitleContainer = styled.div`
  ${tw`
    flex
    flex-col
  `}
`;

const TimeEstimate = styled.p`
  ${tw`
    text-sm
    md:text-lg
    text-black
    font-bold
    flex
    items-center
    py-2
    tracking-wide
  `}
`;

const TimeEstimateIcon = styled(FontAwesomeIcon)`
  ${tw`
    text-cBlue
    mr-2
    text-base
    md:text-lg
  `}
`;

const FeaturesList = styled.ul`
  ${tw`
    list-none
    space-y-2
    my-4
    font-normal
  `}
`;

const FeatureItem = styled.li`
  ${tw`
    flex
    items-center
    text-sm
  `}
  &::before {
    content: "✓";
    ${tw`
      text-green-500
      mr-2
      text-lg
    `}
  }
`;

const NavigationButtons = styled.div`
  ${tw`
    absolute 
    bottom-4 
    left-1/2 
    transform -translate-x-1/2 
    flex
    items-center
    justify-center
    z-10 
  `}
`;

const ActionButton = styled.button`
  ${tw`
    p-5
    bg-cBlue
    text-white
    font-semibold
    rounded-full
    shadow-md
    hover:bg-blue-700
    transition-all
    duration-300
  `}
  width: 100%;
  display: inline-block;
  transition: transform 0.1s ease-in-out;

  &:hover {
    transform: scale(0.95);
  }
`;

const AboutUs = () => {
  const [activeIndex, setActiveIndex] = useState(0);
  const navigate = useNavigate();

  const nextView = () => {
    setActiveIndex((prev) => (prev + 1) % windshieldTypes.length);
  };

  const prevView = () => {
    setActiveIndex(
      (prev) => (prev - 1 + windshieldTypes.length) % windshieldTypes.length
    );
  };

  const windshieldTypes = [
    {
      model: "/models/mazda_model.glb",
      title: "Windshield",
      subtitle: "Premium Protection for Your Vehicle",
      time: "90 minutes",
      features: [
        "Scratch-resistant glass",
        "UV protection coating",
        "Affordable pricing",
      ],
      primitivePos: [-46, -50, -45],
      camPos: [0, 5, -15],
      lookPos: [0, 8, -30],
      rotation: [0, 0, 0],
    },
    {
      model: "/models/mazda_model.glb",
      title: "Side Glass",
      subtitle: "Enhanced Durability and Clarity",
      time: "90 minutes",
      features: [
        "Enhanced clarity",
        "Thermal protection",
        "Premium durability",
      ],
      primitivePos: [-46, -50, -45],
      camPos: [150, -5, -165],
      lookPos: [150, 0, -170],
      rotation: [-170, 90, 170],
    },
    {
      model: "/models/mazda_model.glb",
      title: "Back Glass",
      subtitle: "Enhanced Durability and Clarity",
      time: "120 minutes",
      features: [
        "Enhanced clarity",
        "Thermal protection",
        "Premium durability",
      ],
      primitivePos: [-46, -50, -45],
      camPos: [1.5, 15, -305],
      lookPos: [1.5, 0, -305],
      rotation: [-170, 1, 180],
    },
  ];

  const activeView = windshieldTypes[activeIndex];

  return (
    <SectionContainer>
      <ContentWrapper>
        {/* Car Model */}
        <ModelContainer>
          <CarModelCanvas
            modelPath={activeView.model}
            primitivePos={activeView.primitivePos}
            camPos={activeView.camPos}
            lookPos={activeView.lookPos}
            rotation={activeView.rotation}
          />

          {/* Navigation Buttons Under the Model */}
          <NavigationButtons>
            <ArrowButton onClick={prevView}>❮</ArrowButton>
            <ArrowButton onClick={nextView}>❯</ArrowButton>
          </NavigationButtons>
        </ModelContainer>

        <DescriptionContainer>
          <TitleContainer>
            <Title>{windshieldTypes[activeIndex].title}</Title>
            <TimeEstimate>
              <TimeEstimateIcon icon={faClock} />
              {windshieldTypes[activeIndex].time} minimum
            </TimeEstimate>
          </TitleContainer>

          <FeaturesList>
            {windshieldTypes[activeIndex].features.map((feature, index) => (
              <FeatureItem key={index}>{feature}</FeatureItem>
            ))}
          </FeaturesList>
          <ActionButton
            aria-label={`Get a quote for ${windshieldTypes[activeIndex].title}`}
            onClick={() => navigate("/quote")}
          >
            Get Quote
          </ActionButton>
        </DescriptionContainer>
      </ContentWrapper>
    </SectionContainer>
  );
};

export default AboutUs;
